@extends('layout.app')

@section('content')
<h2>Add Product</h2>
<div class="card border-0">
    <div class="card-body">
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="unit_price" class="form-label">Unit Price (₹)</label>
                <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Initial Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold') }}" required>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save Product</button>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
