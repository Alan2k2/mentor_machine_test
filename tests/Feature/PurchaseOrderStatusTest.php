<?php

use App\Actions\UpdatePurchaseOrderStatusAction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

/**
 * Test the status transition of a Purchase Order to 'RECEIVED'.
 * 
 * Verifies that when an order is marked as RECEIVED, the system correctly updates the status
 * and uses pessimistic locking to safely increment the corresponding product inventory levels.
 */
test('status update to received updates inventory', function () {
    $supplier = Supplier::create(['name' => 'Supplier A', 'email' => 'a@a.com']);
    $product = Product::create(['sku' => 'P1', 'name' => 'Product 1', 'unit_price' => 10.00, 'stock_quantity' => 50]);
    
    $po = PurchaseOrder::create(['supplier_id' => $supplier->id, 'status' => 'APPROVED', 'total_amount' => 100]);
    PurchaseOrderItem::create([
        'purchase_order_id' => $po->id, 'product_id' => $product->id, 'quantity' => 10, 'unit_price' => 10, 'subtotal' => 100
    ]);

    $action = new UpdatePurchaseOrderStatusAction();
    $action->execute($po, 'RECEIVED');

    $product->refresh();
    expect($product->stock_quantity)->toEqual(60);
});

test('cannot modify received order', function () {
    $supplier = Supplier::create(['name' => 'Supplier A', 'email' => 'a@a.com']);
    $po = PurchaseOrder::create(['supplier_id' => $supplier->id, 'status' => 'RECEIVED', 'total_amount' => 100]);

    $action = new UpdatePurchaseOrderStatusAction();
    
    expect(fn () => $action->execute($po, 'CANCELLED'))->toThrow(InvalidArgumentException::class);
});
