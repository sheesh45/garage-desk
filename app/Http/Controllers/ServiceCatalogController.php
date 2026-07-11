<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceCatalogUpsertRequest;
use App\Models\ServiceCatalog;
use Inertia\Inertia;
use Inertia\Response;

class ServiceCatalogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('services/Index', [
            'services' => ServiceCatalog::query()->latest()->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('services/Form', ['service' => null]);
    }

    public function store(ServiceCatalogUpsertRequest $request)
    {
        ServiceCatalog::create($request->validated());

        return to_route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(ServiceCatalog $service): Response
    {
        return Inertia::render('services/Form', ['service' => $service]);
    }

    public function update(ServiceCatalogUpsertRequest $request, ServiceCatalog $service)
    {
        $service->update($request->validated());

        return to_route('services.index')->with('success', 'Service updated successfully.');
    }
}
