<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Render the main dashboard view.
     * 
     * Calculates high-level metrics (total products, low stock alerts, total expenditure)
     * using optimized DB aggregates and retrieves the 5 most recent Purchase Orders.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = \Illuminate\Support\Facades\Cache::remember('dashboard_metrics', 300, function () {
            return [
                'totalProducts' => \App\Models\Product::count(),
                'lowStockCount' => \App\Models\Product::whereColumn('stock_quantity', '<', 'low_stock_threshold')->count(),
                'totalExpenditure' => \App\Models\PurchaseOrder::where('status', 'RECEIVED')->sum('total_amount'),
                'latestOrders' => \App\Models\PurchaseOrder::with('supplier')->latest()->take(5)->get(),
            ];
        });

        return view('dashboard', $data);
    }
}
