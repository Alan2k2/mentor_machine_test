<?php

use App\Actions\CreatePurchaseOrderAction;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('calculates correct order total and item subtotals', function () {
    $supplier = Supplier::create(['name' => 'Supplier A', 'email' => 'a@a.com']);
    $product1 = Product::create(['sku' => 'P1', 'name' => 'Product 1', 'unit_price' => 10.00, 'stock_quantity' => 100]);
    $product2 = Product::create(['sku' => 'P2', 'name' => 'Product 2', 'unit_price' => 20.00, 'stock_quantity' => 100]);

    $data = [
        'supplier_id' => $supplier->id,
        'items' => [
            ['product_id' => $product1->id, 'quantity' => 2, 'unit_price' => 10.00],
            ['product_id' => $product2->id, 'quantity' => 3, 'unit_price' => 20.00],
        ]
    ];

    $action = new CreatePurchaseOrderAction();
    $po = $action->execute($data);

    expect($po->total_amount)->toEqual(80.00);
    expect($po->items)->toHaveCount(2);
    expect($po->items[0]->subtotal)->toEqual(20.00);
    expect($po->items[1]->subtotal)->toEqual(60.00);
});
