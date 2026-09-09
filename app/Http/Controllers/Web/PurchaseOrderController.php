<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display the form for creating a new Purchase Order.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        $products = \App\Models\Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created Purchase Order from the web form.
     * 
     * Validates input and delegates the business logic to CreatePurchaseOrderAction.
     * 
     * @param \App\Http\Requests\StorePurchaseOrderRequest $request
     * @param \App\Actions\CreatePurchaseOrderAction $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\App\Http\Requests\StorePurchaseOrderRequest $request, \App\Actions\CreatePurchaseOrderAction $action)
    {
        try {
            $po = $action->execute($request->validated());
            return redirect()->route('purchase-orders.show', $po->id)->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the details of a specific Purchase Order.
     * 
     * Uses Eloquent Eager Loading to fetch the associated supplier and line items 
     * (with their respective products) to prevent N+1 query issues.
     * 
     * @param int $id The ID of the Purchase Order
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $po = \App\Models\PurchaseOrder::with(['supplier', 'items.product'])->findOrFail($id);
        return view('purchase_orders.show', compact('po'));
    }

    /**
     * Update the status of a specific Purchase Order via a web form.
     * 
     * Delegates validation and state transition logic (including inventory locking) 
     * to the UpdatePurchaseOrderStatusAction.
     * 
     * @param \App\Http\Requests\UpdatePurchaseOrderStatusRequest $request
     * @param int $id The ID of the Purchase Order
     * @param \App\Actions\UpdatePurchaseOrderStatusAction $action
     * @return \Illuminate\Http\RedirectResponse
     */
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
