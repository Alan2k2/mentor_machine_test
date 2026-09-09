@extends('layout.app')

@section('content')
<h2>Create Purchase Order</h2>
<div class="card border-0">
    <div class="card-body">
        <form action="{{ route('purchase-orders.store') }}" method="POST" id="po-form">
            @csrf
            
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-select" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <h4 class="mb-3">Line Items</h4>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <!-- Default first row -->
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select product-select" required>
                                <option value="" data-price="0">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="items[0][unit_price]" class="form-control unit-price" required readonly>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control quantity" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="text" class="form-control subtotal" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Amount:</th>
                        <th id="grand-total">₹0.00</th>
                        <th>
                            <button type="button" class="btn btn-primary btn-sm" id="add-row">Add Item</button>
                        </th>
                    </tr>
                </tfoot>
            </table>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Submit Purchase Order</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let rowIdx = 1;

        function calculateTotals() {
            let grandTotal = 0;
            $('.item-row').each(function() {
                let price = parseFloat($(this).find('.unit-price').val()) || 0;
                let qty = parseInt($(this).find('.quantity').val()) || 0;
                let subtotal = price * qty;
                
                $(this).find('.subtotal').val('₹' + subtotal.toFixed(2));
                grandTotal += subtotal;
            });
            $('#grand-total').text('₹' + grandTotal.toFixed(2));
        }

        $('#add-row').click(function() {
            let newRow = `
                <tr class="item-row">
                    <td>
                        <select name="items[${rowIdx}][product_id]" class="form-select product-select" required>
                            <option value="" data-price="0">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="items[${rowIdx}][unit_price]" class="form-control unit-price" required readonly>
                    </td>
                    <td>
                        <input type="number" name="items[${rowIdx}][quantity]" class="form-control quantity" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                    </td>
                </tr>
            `;
            $('#items-body').append(newRow);
            rowIdx++;
        });

        $(document).on('change', '.product-select', function() {
            let price = $(this).find(':selected').data('price');
            $(this).closest('tr').find('.unit-price').val(price);
            calculateTotals();
        });

        $(document).on('input', '.quantity, .unit-price', function() {
            calculateTotals();
        });

        $(document).on('click', '.remove-row', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('tr').remove();
                calculateTotals();
            } else {
                alert("You must have at least one line item.");
            }
        });
    });
</script>
@endpush
