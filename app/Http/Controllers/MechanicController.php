<?php

namespace App\Http\Controllers;

use App\Http\Requests\MechanicUpsertRequest;
use App\Models\Mechanic;
use Inertia\Inertia;
use Inertia\Response;

class MechanicController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('mechanics/Index', [
            'mechanics' => Mechanic::query()
                ->withCount([
                    'jobCards',
                    'jobCards as completed_jobs_count' => fn ($query) => $query->where('status', 'completed'),
                ])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('mechanics/Form', ['mechanic' => null]);
    }

    public function store(MechanicUpsertRequest $request)
    {
        Mechanic::create($request->validated());

        return to_route('mechanics.index')->with('success', 'Mechanic created successfully.');
    }

    public function edit(Mechanic $mechanic): Response
    {
        return Inertia::render('mechanics/Form', ['mechanic' => $mechanic]);
    }

    public function update(MechanicUpsertRequest $request, Mechanic $mechanic)
    {
        $mechanic->update($request->validated());

        return to_route('mechanics.index')->with('success', 'Mechanic updated successfully.');
    }
}
