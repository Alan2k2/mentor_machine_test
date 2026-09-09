<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        $products = \App\Models\Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(\App\Http\Requests\StorePurchaseOrderRequest $request, \App\Actions\CreatePurchaseOrderAction $action)
    {
        try {
            $po = $action->execute($request->validated());
            return redirect()->route('purchase-orders.show', $po->id)->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $po = \App\Models\PurchaseOrder::with(['supplier', 'items.product'])->findOrFail($id);
        return view('purchase_orders.show', compact('po'));
    }

    public function updateStatus(\App\Http\Requests\UpdatePurchaseOrderStatusRequest $request, $id, \App\Actions\UpdatePurchaseOrderStatusAction $action)
    {
        $po = \App\Models\PurchaseOrder::findOrFail($id);
        
        try {
            $action->execute($po, $request->status);
            return redirect()->route('purchase-orders.show', $po->id)->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
