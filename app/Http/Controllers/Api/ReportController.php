<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Generate a report calculating the total spend across all RECEIVED purchase orders, grouped by supplier.
     * 
     * Uses optimized DB aggregate functions (SUM) and GROUP BY to calculate 
     * the totals efficiently on the database side without hydrating large collections.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function supplierSpend()
    {
        $spend = \Illuminate\Support\Facades\Cache::remember('api_report_supplier_spend', 300, function () {
            return \App\Models\PurchaseOrder::where('status', 'RECEIVED')
                ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                ->select('suppliers.id', 'suppliers.name', \Illuminate\Support\Facades\DB::raw('SUM(purchase_orders.total_amount) as total_spend'))
                ->groupBy('suppliers.id', 'suppliers.name')
                ->get();
        });

        return response()->json($spend);
    }
}
