<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Store a newly created Purchase Order in the database.
     * 
     * Validates the incoming payload, calculates subtotals and the total amount,
     * and delegates the creation logic to the CreatePurchaseOrderAction class.
     * 
     * @param \App\Http\Requests\StorePurchaseOrderRequest $request
     * @param \App\Actions\CreatePurchaseOrderAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\App\Http\Requests\StorePurchaseOrderRequest $request, \App\Actions\CreatePurchaseOrderAction $action)
    {
        try {
            $po = $action->execute($request->validated());
            return response()->json($po, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Update the status of a specific Purchase Order.
     * 
     * Validates the status transition rules (e.g., cannot update a CANCELLED or RECEIVED order).
     * If transitioning to RECEIVED, it uses pessimistic locking to safely mutate inventory levels.
     * 
     * @param \App\Http\Requests\UpdatePurchaseOrderStatusRequest $request
     * @param int $id The ID of the Purchase Order
     * @param \App\Actions\UpdatePurchaseOrderStatusAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(\App\Http\Requests\UpdatePurchaseOrderStatusRequest $request, $id, \App\Actions\UpdatePurchaseOrderStatusAction $action)
    {
        $po = \App\Models\PurchaseOrder::findOrFail($id);
        
        try {
            $updatedPo = $action->execute($po, $request->status);
            return response()->json($updatedPo);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
