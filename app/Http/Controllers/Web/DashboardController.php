<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = \App\Models\Product::count();
        $lowStockCount = \App\Models\Product::whereColumn('stock_quantity', '<', 'low_stock_threshold')->count();
        $totalExpenditure = \App\Models\PurchaseOrder::where('status', 'RECEIVED')->sum('total_amount');
        
        $latestOrders = \App\Models\PurchaseOrder::with('supplier')->latest()->take(5)->get();

        return view('dashboard', compact('totalProducts', 'lowStockCount', 'totalExpenditure', 'latestOrders'));
    }
}
