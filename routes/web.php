<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobCardController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceCatalogController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('customers', CustomerController::class)->except(['destroy']);
    Route::resource('vehicles', VehicleController::class)->except(['show', 'destroy']);
    Route::resource('mechanics', MechanicController::class)->except(['show', 'destroy']);
    Route::resource('inventory', InventoryItemController::class)
        ->parameters(['inventory' => 'inventoryItem'])
        ->except(['show', 'destroy']);
    Route::resource('services', ServiceCatalogController::class)->except(['show', 'destroy']);
    Route::resource('jobs', JobCardController::class)->parameters(['jobs' => 'job'])->except(['destroy']);
    Route::resource('invoices', InvoiceController::class)->parameters(['invoices' => 'invoice'])->except(['edit', 'update', 'destroy']);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

    Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
});

require __DIR__.'/settings.php';
