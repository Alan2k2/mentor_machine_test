<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Render the Supplier Spend Report view.
     * 
     * Uses optimized DB aggregations (SUM) and GROUP BY to fetch the total amount spent 
     * per supplier for all RECEIVED purchase orders.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $supplierSpend = \Illuminate\Support\Facades\Cache::remember('report_supplier_spend', 300, function () {
            return \App\Models\PurchaseOrder::where('status', 'RECEIVED')
                ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                ->select('suppliers.id', 'suppliers.name', \Illuminate\Support\Facades\DB::raw('SUM(purchase_orders.total_amount) as total_spend'))
                ->groupBy('suppliers.id', 'suppliers.name')
                ->orderByDesc('total_spend')
                ->get();
        });

        return view('reports.index', compact('supplierSpend'));
    }
}
