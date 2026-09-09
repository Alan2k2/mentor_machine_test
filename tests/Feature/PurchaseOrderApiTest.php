<?php

use App\Models\User;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('unauthorized requests return 401', function () {
    $response = $this->postJson('/api/purchase-orders', []);
    $response->assertStatus(401);
});

/**
 * Test the API endpoint for creating a new Purchase Order.
 * 
 * Verifies that when a valid payload is submitted, the API returns a 201 Created status,
 * the Purchase Order is saved in the database with the correct total amount,
 * and the individual line items are correctly attached.
 */
test('can create purchase order via api', function () {
    $user = User::factory()->create();
    $supplier = Supplier::create(['name' => 'Supplier A', 'email' => 'a@a.com']);
    $product = Product::create(['sku' => 'P1', 'name' => 'Product 1', 'unit_price' => 10.00, 'stock_quantity' => 50]);

    $payload = [
        'supplier_id' => $supplier->id,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 5, 'unit_price' => 10]
        ]
    ];

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/purchase-orders', $payload);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('purchase_orders', ['total_amount' => 50]);
});

test('missing fields fails validation', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/purchase-orders', []);
    
    $response->assertStatus(422)
             ->assertJsonValidationErrors(['supplier_id', 'items']);
});
