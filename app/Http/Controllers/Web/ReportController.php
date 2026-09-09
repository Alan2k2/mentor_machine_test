<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $supplierSpend = \App\Models\PurchaseOrder::where('status', 'RECEIVED')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.id', 'suppliers.name', \Illuminate\Support\Facades\DB::raw('SUM(purchase_orders.total_amount) as total_spend'))
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_spend')
            ->get();

        return view('reports.index', compact('supplierSpend'));
    }
}
