<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryItemUpsertRequest;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Inertia\Inertia;
use Inertia\Response;

class InventoryItemController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('inventory/Index', [
            'items' => InventoryItem::query()
                ->with('supplier')
                ->when(request('search'), function ($query, string $search): void {
                    $query->where(function ($innerQuery) use ($search): void {
                        $innerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%");
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
        return Inertia::render('inventory/Form', [
            'item' => null,
            'suppliers' => Supplier::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(InventoryItemUpsertRequest $request)
    {
        InventoryItem::create($request->validated());

        return to_route('inventory.index')->with('success', 'Inventory item created successfully.');
    }

    public function edit(InventoryItem $inventoryItem): Response
    {
        return Inertia::render('inventory/Form', [
            'item' => $inventoryItem,
            'suppliers' => Supplier::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(InventoryItemUpsertRequest $request, InventoryItem $inventoryItem)
    {
        $inventoryItem->update($request->validated());

        return to_route('inventory.index')->with('success', 'Inventory item updated successfully.');
    }
}
