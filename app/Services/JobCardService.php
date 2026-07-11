<?php

namespace App\Services;

use App\Enums\JobCardStatus;
use App\Enums\StockMovementType;
use App\Events\JobCardCompleted;
use App\Models\InventoryItem;
use App\Models\JobCard;
use App\Models\ServiceCatalog;
use Illuminate\Support\Facades\DB;

class JobCardService
{
    public function create(array $attributes, ?int $userId = null): JobCard
    {
        return DB::transaction(function () use ($attributes, $userId): JobCard {
            $jobCard = JobCard::create([
                ...$attributes,
                'job_number' => 'JOB-'.str_pad((string) ((JobCard::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT),
                'created_by' => $userId,
                'check_in_at' => now(),
                'time_taken_minutes' => $attributes['time_taken_minutes'] ?? 0,
            ]);

            $this->syncServices($jobCard, $attributes['services'] ?? []);
            $this->syncParts($jobCard, $attributes['parts'] ?? []);
            $this->handleStatusTransitions($jobCard);

            return $jobCard->load(['customer', 'vehicle', 'mechanic', 'services', 'parts']);
        });
    }

    public function update(JobCard $jobCard, array $attributes): JobCard
    {
        return DB::transaction(function () use ($jobCard, $attributes): JobCard {
            $jobCard->update([
                ...$attributes,
                'time_taken_minutes' => $attributes['time_taken_minutes'] ?? $jobCard->time_taken_minutes,
            ]);

            $this->syncServices($jobCard, $attributes['services'] ?? []);
            $this->syncParts($jobCard, $attributes['parts'] ?? []);
            $this->handleStatusTransitions($jobCard);

            return $jobCard->load(['customer', 'vehicle', 'mechanic', 'services', 'parts']);
        });
    }

    protected function syncServices(JobCard $jobCard, array $serviceIds): void
    {
        $serviceRows = ServiceCatalog::query()
            ->whereIn('id', $serviceIds)
            ->get()
            ->mapWithKeys(fn (ServiceCatalog $service): array => [
                $service->id => [
                    'quantity' => 1,
                    'unit_price' => $service->base_cost,
                    'total' => $service->base_cost,
                ],
            ])
            ->all();

        $jobCard->services()->sync($serviceRows);
    }

    protected function syncParts(JobCard $jobCard, array $parts): void
    {
        $syncData = [];
        $currentParts = $jobCard->parts()->pluck('job_card_part.quantity', 'inventory_items.id');

        foreach ($parts as $part) {
            $item = InventoryItem::query()->find($part['id'] ?? null);

            if (! $item) {
                continue;
            }

            $quantity = (int) ($part['quantity'] ?? 1);

            $syncData[$item->id] = [
                'quantity' => $quantity,
                'unit_price' => $item->selling_price,
                'total' => $item->selling_price * $quantity,
            ];
        }

        $jobCard->parts()->sync($syncData);

        foreach ($syncData as $itemId => $partData) {
            $item = InventoryItem::query()->find($itemId);

            if (! $item) {
                continue;
            }

            $existingQuantity = (int) ($currentParts[$itemId] ?? 0);
            $delta = $partData['quantity'] - $existingQuantity;

            if ($delta === 0) {
                continue;
            }

            if ($delta > 0) {
                $item->decrement('quantity', $delta);
            } else {
                $item->increment('quantity', abs($delta));
            }

            $item->movements()->create([
                'job_card_id' => $jobCard->id,
                'type' => $delta > 0 ? StockMovementType::Out : StockMovementType::Adjustment,
                'quantity' => abs($delta),
                'reference' => $jobCard->job_number,
                'moved_at' => now(),
            ]);
        }

        foreach ($currentParts as $itemId => $existingQuantity) {
            if (array_key_exists($itemId, $syncData)) {
                continue;
            }

            $item = InventoryItem::query()->find($itemId);

            if (! $item) {
                continue;
            }

            $item->increment('quantity', (int) $existingQuantity);

            $item->movements()->create([
                'job_card_id' => $jobCard->id,
                'type' => StockMovementType::Adjustment,
                'quantity' => (int) $existingQuantity,
                'reference' => $jobCard->job_number,
                'notes' => 'Part removed from job card during update.',
                'moved_at' => now(),
            ]);
        }
    }

    protected function handleStatusTransitions(JobCard $jobCard): void
    {
        if ($jobCard->status === JobCardStatus::Completed && ! $jobCard->completed_at) {
            $jobCard->forceFill(['completed_at' => now()])->save();
            JobCardCompleted::dispatch($jobCard);
        }

        if ($jobCard->status === JobCardStatus::Delivered && ! $jobCard->delivered_at) {
            $jobCard->forceFill(['delivered_at' => now()])->save();
        }
    }
}
