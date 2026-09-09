@extends('layout.app')

@section('content')
<div class="page-header mt-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Overview</h2>
        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
    </div>
    <div>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="ph-bold ph-plus"></i> New Order
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card metric-card mb-3 border-primary">
            <div class="card-body p-0">
                <div class="metric-label text-primary mb-2">Total Active Products</div>
                <div class="metric-value">{{ $totalProducts }}</div>
            </div>
            <div class="icon-wrapper bg-primary text-primary" style="--bs-bg-opacity: .15;">
                <i class="ph-fill ph-package"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric-card mb-3 border-danger">
            <div class="card-body p-0">
                <div class="metric-label text-danger mb-2">Low Stock Alerts</div>
                <div class="metric-value">{{ $lowStockCount }}</div>
            </div>
            <div class="icon-wrapper bg-danger text-danger" style="--bs-bg-opacity: .15;">
                <i class="ph-fill ph-warning-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric-card mb-3 border-success">
            <div class="card-body p-0">
                <div class="metric-label text-success mb-2">Total Expenditure</div>
                <div class="metric-value">₹{{ number_format($totalExpenditure, 2) }}</div>
            </div>
            <div class="icon-wrapper bg-success text-success" style="--bs-bg-opacity: .15;">
                <i class="ph-fill ph-currency-inr"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0">
    <div class="card-header border-0 fs-5">
        Latest Purchase Orders
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->supplier->name }}</td>
                    <td>
                        <span class="badge 
                            @if($order->status == 'DRAFT') bg-secondary 
                            @elseif($order->status == 'APPROVED') bg-primary 
                            @elseif($order->status == 'RECEIVED') bg-success 
                            @elseif($order->status == 'CANCELLED') bg-danger 
                            @endif">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('purchase-orders.show', $order->id) }}" class="btn btn-sm btn-info text-white">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No purchase orders found.</td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
