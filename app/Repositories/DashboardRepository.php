<?php

namespace App\Repositories;

use App\Enums\JobCardStatus;
use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\JobCard;
use App\Models\Mechanic;
use App\Models\ServiceReminder;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DashboardRepository
{
    public function metrics(): array
    {
        return [
            'today_jobs' => JobCard::query()->whereDate('created_at', today())->count(),
            'pending_jobs' => JobCard::query()->whereIn('status', ['pending', 'inspection', 'waiting_parts', 'in_progress'])->count(),
            'completed_jobs' => JobCard::query()->where('status', 'completed')->count(),
            'revenue_today' => (float) Invoice::query()->whereDate('issued_at', today())->sum('grand_total'),
            'revenue_month' => (float) Invoice::query()->whereBetween('issued_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('grand_total'),
            'low_stock_items' => InventoryItem::query()->whereColumn('quantity', '<=', 'minimum_stock')->count(),
            'active_mechanics' => Mechanic::query()->where('is_active', true)->count(),
            'upcoming_reminders' => ServiceReminder::query()->whereBetween('due_at', [now(), now()->copy()->addDays(14)])->count(),
        ];
    }

    public function charts(): array
    {
        $monthlyRevenue = Collection::times(6, function (int $offset): array {
            $month = now()->startOfMonth()->subMonths(6 - $offset);

            return [
                'label' => $month->format('M'),
                'value' => (float) Invoice::query()
                    ->whereBetween('issued_at', [$month, $month->copy()->endOfMonth()])
                    ->sum('grand_total'),
            ];
        });

        $jobStatus = JobCard::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function ($row): array {
                $status = $row->status instanceof JobCardStatus
                    ? $row->status->value
                    : (string) $row->status;

                return [
                    'label' => str($status)->replace('_', ' ')->title()->value(),
                    'value' => (int) $row->total,
                ];
            })
            ->values();

        $vehicleTypes = Vehicle::query()
            ->select('vehicle_type', DB::raw('COUNT(*) as total'))
            ->groupBy('vehicle_type')
            ->get()
            ->map(fn ($row): array => ['label' => str($row->vehicle_type)->title()->value(), 'value' => (int) $row->total])
            ->values();

        $inventoryUsage = DB::table('job_card_part')
            ->join('inventory_items', 'inventory_items.id', '=', 'job_card_part.inventory_item_id')
            ->select('inventory_items.name', DB::raw('SUM(job_card_part.quantity) as total'))
            ->groupBy('inventory_items.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row): array => ['label' => $row->name, 'value' => (int) $row->total])
            ->values();

        return compact('monthlyRevenue', 'jobStatus', 'vehicleTypes', 'inventoryUsage');
    }
}
