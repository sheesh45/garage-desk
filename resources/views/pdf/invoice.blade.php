<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        .row { margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .summary { margin-top: 16px; width: 320px; margin-left: auto; }
    </style>
</head>
<body>
    <div class="row">
        <h1>GarageFlow Invoice</h1>
        <p>Invoice: {{ $invoice->invoice_number }}</p>
        <p>Issued: {{ $invoice->issued_at?->format('d M Y h:i A') }}</p>
    </div>

    <div class="row">
        <strong>Customer:</strong> {{ $invoice->jobCard?->customer?->name }}<br>
        <strong>Vehicle:</strong> {{ $invoice->jobCard?->vehicle?->registration_number }}<br>
        <strong>Complaint:</strong> {{ $invoice->jobCard?->complaint }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr><th>Labour</th><td>{{ number_format($invoice->labour_total, 2) }}</td></tr>
        <tr><th>Parts</th><td>{{ number_format($invoice->parts_total, 2) }}</td></tr>
        <tr><th>Discount</th><td>{{ number_format($invoice->discount_total, 2) }}</td></tr>
        <tr><th>Tax</th><td>{{ number_format($invoice->tax_total, 2) }}</td></tr>
        <tr><th>Grand Total</th><td>{{ number_format($invoice->grand_total, 2) }}</td></tr>
    </table>
</body>
</html>
