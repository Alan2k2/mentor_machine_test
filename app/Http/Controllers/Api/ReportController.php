<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function supplierSpend()
    {
        $spend = \App\Models\PurchaseOrder::where('status', 'RECEIVED')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.id', 'suppliers.name', \Illuminate\Support\Facades\DB::raw('SUM(purchase_orders.total_amount) as total_spend'))
            ->groupBy('suppliers.id', 'suppliers.name')
            ->get();

        return response()->json($spend);
    }
}
