<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerUpsertRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(CustomerRepository $customerRepository): Response
    {
        return Inertia::render('customers/Index', [
            'customers' => $customerRepository->paginatedWithSearch(request('search')),
            'filters' => request()->only('search'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('customers/Form', [
            'customer' => null,
        ]);
    }

    public function store(CustomerUpsertRequest $request)
    {
        Customer::create($request->validated());

        return to_route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): Response
    {
        return Inertia::render('customers/Show', [
            'customer' => $customer->load(['vehicles', 'jobCards.vehicle', 'jobCards.mechanic']),
        ]);
    }

    public function edit(Customer $customer): Response
    {
        return Inertia::render('customers/Form', [
            'customer' => $customer,
        ]);
    }

    public function update(CustomerUpsertRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return to_route('customers.index')->with('success', 'Customer updated successfully.');
    }
}
