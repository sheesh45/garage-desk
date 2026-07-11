<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleUpsertRequest;
use App\Models\Customer;
use App\Models\Vehicle;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('vehicles/Index', [
            'vehicles' => Vehicle::query()
                ->with('customer')
                ->when(request('search'), function ($query, string $search): void {
                    $query->where(function ($innerQuery) use ($search): void {
                        $innerQuery
                            ->where('registration_number', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filters' => request()->only('search'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('vehicles/Form', [
            'vehicle' => null,
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(VehicleUpsertRequest $request)
    {
        Vehicle::create([
            ...$request->validated(),
            'qr_code' => 'VEH-'.$request->string('registration_number')->value(),
        ]);

        return to_route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    public function edit(Vehicle $vehicle): Response
    {
        return Inertia::render('vehicles/Form', [
            'vehicle' => $vehicle,
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(VehicleUpsertRequest $request, Vehicle $vehicle)
    {
        $vehicle->update([
            ...$request->validated(),
            'qr_code' => 'VEH-'.$request->string('registration_number')->value(),
        ]);

        return to_route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }
}
