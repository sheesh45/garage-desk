<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceStoreRequest;
use App\Models\Invoice;
use App\Models\JobCard;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('invoices/Index', [
            'invoices' => Invoice::query()
                ->with(['jobCard.customer', 'jobCard.vehicle'])
                ->latest('issued_at')
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('invoices/Form', [
            'jobs' => JobCard::query()
                ->doesntHave('invoice')
                ->with(['customer', 'vehicle', 'services', 'parts'])
                ->latest()
                ->get(),
        ]);
    }

    public function store(InvoiceStoreRequest $request, InvoiceService $invoiceService)
    {
        $jobCard = JobCard::query()->with(['services', 'parts'])->findOrFail($request->integer('job_card_id'));
        $invoice = $invoiceService->createFromJobCard($jobCard, $request->validated());

        return to_route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): Response
    {
        return Inertia::render('invoices/Show', [
            'invoice' => $invoice->load(['jobCard.customer', 'jobCard.vehicle', 'items', 'payments']),
        ]);
    }

    public function pdf(Invoice $invoice): HttpResponse
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice->load(['jobCard.customer', 'jobCard.vehicle', 'items']),
        ]);

        return $pdf->download("{$invoice->invoice_number}.pdf");
    }
}
