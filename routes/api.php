<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Authentication Endpoint: Issues Sanctum tokens for API access
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Return currently authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Inventory Endpoints
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock']); // Get items below threshold
    Route::get('/inventory', [InventoryController::class, 'index']); // List all inventory items
    
    // Purchase Order Endpoints
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store']); // Create new PO
    Route::patch('/purchase-orders/{id}/status', [PurchaseOrderController::class, 'updateStatus']); // Update PO status
    
    // Reporting Endpoints
    Route::get('/reports/supplier-spend', [ReportController::class, 'supplierSpend']); // Calculate total spend per supplier
});
