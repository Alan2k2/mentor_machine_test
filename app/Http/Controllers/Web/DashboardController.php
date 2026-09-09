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
        $metrics = \Illuminate\Support\Facades\Cache::remember('dashboard_metrics', 300, function () {
            return [
                'totalProducts' => \App\Models\Product::count(),
                'lowStockCount' => \App\Models\Product::whereColumn('stock_quantity', '<', 'low_stock_threshold')->count(),
                'totalExpenditure' => \App\Models\PurchaseOrder::where('status', 'RECEIVED')->sum('total_amount'),
            ];
        });

        // Eloquent objects should generally not be cached directly in Redis to avoid 
        // __PHP_Incomplete_Class serialization errors when dependencies change.
        // A LIMIT 5 query is extremely fast and doesn't need to be cached.
        $latestOrders = \App\Models\PurchaseOrder::with('supplier')->latest()->take(5)->get();

        return view('dashboard', [
            'totalProducts' => $metrics['totalProducts'],
            'lowStockCount' => $metrics['lowStockCount'],
            'totalExpenditure' => $metrics['totalExpenditure'],
            'latestOrders' => $latestOrders
        ]);
    }
}
