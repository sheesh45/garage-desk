<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Invoice::query()
            ->with(['jobCard.customer', 'jobCard.vehicle'])
            ->latest('issued_at')
            ->get()
            ->map(fn (Invoice $invoice) => [
                'invoice_number' => $invoice->invoice_number,
                'date' => $invoice->issued_at?->toDateString(),
                'customer' => $invoice->jobCard?->customer?->name,
                'vehicle' => $invoice->jobCard?->vehicle?->registration_number,
                'status' => $invoice->payment_status?->value,
                'total' => $invoice->grand_total,
            ]);
    }

    public function headings(): array
    {
        return ['Invoice #', 'Date', 'Customer', 'Vehicle', 'Payment Status', 'Grand Total'];
    }
}
