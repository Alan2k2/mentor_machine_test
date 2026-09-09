<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Admin Test User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Admin@123'),
        ]);

        // 3 Suppliers
        $suppliers = [
            ['name' => 'Tech Supplies Inc.', 'email' => 'contact@techsupplies.com', 'phone' => '1234567890'],
            ['name' => 'Office Needs Ltd', 'email' => 'sales@officeneeds.com', 'phone' => '0987654321'],
            ['name' => 'Global Logistics', 'email' => 'info@globallogistics.com', 'phone' => '1122334455'],
        ];

        foreach ($suppliers as $supplier) {
            \App\Models\Supplier::create($supplier);
        }

        // 10 Inventory Items (Products)
        $products = [
            ['sku' => 'PRD-001', 'name' => 'Laptop Pro', 'unit_price' => 1200.00, 'stock_quantity' => 50, 'low_stock_threshold' => 10],
            ['sku' => 'PRD-002', 'name' => 'Wireless Mouse', 'unit_price' => 25.00, 'stock_quantity' => 200, 'low_stock_threshold' => 30],
            ['sku' => 'PRD-003', 'name' => 'Mechanical Keyboard', 'unit_price' => 80.00, 'stock_quantity' => 100, 'low_stock_threshold' => 15],
            ['sku' => 'PRD-004', 'name' => '27-inch Monitor', 'unit_price' => 300.00, 'stock_quantity' => 40, 'low_stock_threshold' => 5],
            ['sku' => 'PRD-005', 'name' => 'Office Chair', 'unit_price' => 150.00, 'stock_quantity' => 20, 'low_stock_threshold' => 10],
            ['sku' => 'PRD-006', 'name' => 'Standing Desk', 'unit_price' => 400.00, 'stock_quantity' => 15, 'low_stock_threshold' => 5],
            ['sku' => 'PRD-007', 'name' => 'USB-C Dock', 'unit_price' => 120.00, 'stock_quantity' => 60, 'low_stock_threshold' => 15],
            ['sku' => 'PRD-008', 'name' => 'Noise Cancelling Headphones', 'unit_price' => 250.00, 'stock_quantity' => 30, 'low_stock_threshold' => 8],
            ['sku' => 'PRD-009', 'name' => 'Webcam 1080p', 'unit_price' => 60.00, 'stock_quantity' => 120, 'low_stock_threshold' => 20],
            ['sku' => 'PRD-010', 'name' => 'External SSD 1TB', 'unit_price' => 100.00, 'stock_quantity' => 80, 'low_stock_threshold' => 10],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
