<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Retrieve a paginated list of all products in the inventory.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = \App\Models\Product::paginate(10);
        return response()->json($products);
    }

    /**
     * Retrieve a list of products whose stock quantity has fallen below the predefined low-stock threshold.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function lowStock()
    {
        $products = \App\Models\Product::whereColumn('stock_quantity', '<', 'low_stock_threshold')->get();
        return response()->json($products);
    }
}
