@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Inventory Overview</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary">Add Product</a>
</div>
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Unit Price</th>
                    <th>Stock Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="{{ $product->stock_quantity < $product->low_stock_threshold ? 'table-warning' : '' }}">
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>₹{{ number_format($product->unit_price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        @if ($product->stock_quantity < $product->low_stock_threshold)
                            <span class="badge bg-danger">Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('inventory.edit', $product->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        
        <div class="mt-3 p-3 border-top border-secondary">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
