<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function store(\App\Http\Requests\StorePurchaseOrderRequest $request, \App\Actions\CreatePurchaseOrderAction $action)
    {
        try {
            $po = $action->execute($request->validated());
            return response()->json($po, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

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
