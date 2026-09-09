<?php

namespace App\Actions;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class CreatePurchaseOrderAction
{
    /**
     * Create a new purchase order with items.
     *
     * @param array $data
     * @return PurchaseOrder
     * @throws Exception
     */
    public function execute(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            $totalAmount = 0;
            $items = [];

            // Calculate totals and validate products exist
            foreach ($data['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                $totalAmount += $subtotal;

                $items[] = new PurchaseOrderItem([
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Create PO
            $po = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'status' => 'DRAFT',
                'total_amount' => $totalAmount,
            ]);

            // Save Items
            $po->items()->saveMany($items);

            return $po->load('items');
        });
    }
}
