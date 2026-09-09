<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\PurchaseOrderController;
use App\Http\Controllers\Web\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/reports', [\App\Http\Controllers\Web\ReportController::class, 'index'])->name('reports.index');

    Route::resource('inventory', InventoryController::class)->except(['show']);

    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::patch('/purchase-orders/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
});
