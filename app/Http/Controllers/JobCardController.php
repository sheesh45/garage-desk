<?php

namespace App\Http\Controllers;

use App\Enums\JobCardPriority;
use App\Enums\JobCardStatus;
use App\Http\Requests\JobCardUpsertRequest;
use App\Models\Customer;
use App\Models\InventoryItem;
use App\Models\JobCard;
use App\Models\Mechanic;
use App\Models\ServiceCatalog;
use App\Models\Vehicle;
use App\Repositories\JobCardRepository;
use App\Services\JobCardService;
use Inertia\Inertia;
use Inertia\Response;

class JobCardController extends Controller
{
    public function index(JobCardRepository $jobCardRepository): Response
    {
        return Inertia::render('jobs/Index', [
            'jobs' => $jobCardRepository->paginated(request()->only('search', 'status', 'mechanic_id')),
            'filters' => request()->only('search', 'status', 'mechanic_id'),
            'statuses' => JobCardStatus::values(),
            'mechanics' => Mechanic::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('jobs/Form', $this->formData(null));
    }

    public function store(JobCardUpsertRequest $request, JobCardService $jobCardService)
    {
        $jobCardService->create($request->validated(), $request->user()?->id);

        return to_route('jobs.index')->with('success', 'Job card created successfully.');
    }

    public function show(JobCard $job): Response
    {
        return Inertia::render('jobs/Show', [
            'job' => $job->load(['customer', 'vehicle', 'mechanic', 'services', 'parts', 'invoice']),
        ]);
    }

    public function edit(JobCard $job): Response
    {
        return Inertia::render('jobs/Form', $this->formData($job->load(['services', 'parts'])));
    }

    public function update(JobCardUpsertRequest $request, JobCard $job, JobCardService $jobCardService)
    {
        $jobCardService->update($job, $request->validated());

        return to_route('jobs.index')->with('success', 'Job card updated successfully.');
    }

    protected function formData(?JobCard $job): array
    {
        return [
            'job' => $job,
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name']),
            'vehicles' => Vehicle::query()->orderBy('registration_number')->get(['id', 'customer_id', 'registration_number']),
            'mechanics' => Mechanic::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'services' => ServiceCatalog::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'base_cost']),
            'parts' => InventoryItem::query()->orderBy('name')->get(['id', 'name', 'selling_price', 'quantity']),
            'statuses' => JobCardStatus::values(),
            'priorities' => JobCardPriority::values(),
        ];
    }
}
