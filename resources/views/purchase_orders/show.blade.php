@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Purchase Order #{{ $po->id }}</h2>
    <div>
        <span class="badge fs-5
            @if($po->status == 'DRAFT') bg-secondary 
            @elseif($po->status == 'APPROVED') bg-primary 
            @elseif($po->status == 'RECEIVED') bg-success 
            @elseif($po->status == 'CANCELLED') bg-danger 
            @endif">
            {{ $po->status }}
        </span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3 border-0">
            <div class="card-header border-0 fs-5">Supplier Details</div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $po->supplier->name }}</p>
                <p><strong>Email:</strong> {{ $po->supplier->email }}</p>
                <p><strong>Phone:</strong> {{ $po->supplier->phone }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3 border-0">
            <div class="card-header border-0 fs-5">Order Info</div>
            <div class="card-body">
                <p><strong>Date Created:</strong> {{ $po->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>Total Amount:</strong> ₹{{ number_format($po->total_amount, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border-0">
    <div class="card-header border-0 fs-5">Line Items</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Product SKU</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($po->items as $item)
                <tr>
                    <td>{{ $item->product->sku }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Grand Total:</th>
                    <th>₹{{ number_format($po->total_amount, 2) }}</th>
                </tr>
            </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Action Buttons -->
@if(!in_array($po->status, ['RECEIVED', 'CANCELLED']))
<div class="card border-0">
    <div class="card-header border-0 fs-5">Lifecycle Actions</div>
    <div class="card-body d-flex gap-2">
        @if($po->status === 'DRAFT')
            <form action="{{ route('purchase-orders.update-status', $po->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="APPROVED">
                <button type="submit" class="btn btn-primary">Approve Order</button>
            </form>
        @endif

        @if($po->status === 'APPROVED')
            <form action="{{ route('purchase-orders.update-status', $po->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="RECEIVED">
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure? This will permanently mutate inventory.')">Mark as Received</button>
            </form>
        @endif

        <form action="{{ route('purchase-orders.update-status', $po->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="CANCELLED">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this order?')">Cancel Order</button>
        </form>
    </div>
</div>
@endif

@endsection
