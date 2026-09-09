<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::paginate(10);
        return response()->json($products);
    }

    public function lowStock()
    {
        $products = \App\Models\Product::whereColumn('stock_quantity', '<', 'low_stock_threshold')->get();
        return response()->json($products);
    }
}
