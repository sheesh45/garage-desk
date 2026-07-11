<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobCard;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function createFromJobCard(JobCard $jobCard, array $attributes): Invoice
    {
        return DB::transaction(function () use ($jobCard, $attributes): Invoice {
            $labourTotal = (float) $jobCard->services->sum(fn ($service) => $service->pivot->total);
            $partsTotal = (float) $jobCard->parts->sum(fn ($part) => $part->pivot->total);
            $discountTotal = (float) ($attributes['discount_total'] ?? 0);
            $taxTotal = (float) ($attributes['tax_total'] ?? 0);
            $grandTotal = max(0, $labourTotal + $partsTotal + $taxTotal - $discountTotal);

            $invoice = Invoice::create([
                'job_card_id' => $jobCard->id,
                'invoice_number' => 'INV-'.str_pad((string) ((Invoice::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT),
                'issued_at' => now(),
                'labour_total' => $labourTotal,
                'parts_total' => $partsTotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
                'payment_status' => $attributes['payment_status'] ?? PaymentStatus::Pending->value,
                'notes' => $attributes['notes'] ?? null,
            ]);

            foreach ($jobCard->services as $service) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'type' => 'labour',
                    'description' => $service->name,
                    'quantity' => $service->pivot->quantity,
                    'unit_price' => $service->pivot->unit_price,
                    'total' => $service->pivot->total,
                ]);
            }

            foreach ($jobCard->parts as $part) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'type' => 'part',
                    'description' => $part->name,
                    'quantity' => $part->pivot->quantity,
                    'unit_price' => $part->pivot->unit_price,
                    'total' => $part->pivot->total,
                ]);
            }

            return $invoice->load(['jobCard.customer', 'jobCard.vehicle', 'items']);
        });
    }
}
