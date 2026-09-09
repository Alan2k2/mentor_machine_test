<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::paginate(20);
        return view('inventory.index', compact('products'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        \App\Models\Product::create($validated);
        return redirect()->route('inventory.index')->with('success', 'Product added successfully.');
    }

    public function edit(\App\Models\Product $inventory)
    {
        return view('inventory.edit', ['product' => $inventory]);
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Product $inventory)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku,' . $inventory->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $inventory->update($validated);
        return redirect()->route('inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(\App\Models\Product $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully.');
    }
}
