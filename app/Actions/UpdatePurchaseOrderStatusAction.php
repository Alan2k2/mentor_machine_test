<?php

namespace App\Actions;

use App\Models\PurchaseOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;
use InvalidArgumentException;

class UpdatePurchaseOrderStatusAction
{
    /**
     * Update the status of a purchase order and mutate inventory if received.
     *
     * @param PurchaseOrder $po
     * @param string $newStatus
     * @return PurchaseOrder
     * @throws Exception
     */
    public function execute(PurchaseOrder $po, string $newStatus): PurchaseOrder
    {
        // Validation: Immutability
        if (in_array($po->status, ['RECEIVED', 'CANCELLED'])) {
            throw new InvalidArgumentException("Order is immutable once {$po->status}.");
        }

        // Validation: Valid transitions (simplified logic based on typical rules, allowing backwards to DRAFT from APPROVED is usually fine, but let's restrict backward from RECEIVED/CANCELLED as above)
        $validStatuses = ['DRAFT', 'APPROVED', 'RECEIVED', 'CANCELLED'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new InvalidArgumentException("Invalid status: {$newStatus}");
        }

        // Ensure we don't just update to the same status without reason
        if ($po->status === $newStatus) {
            return $po;
        }

        return DB::transaction(function () use ($po, $newStatus) {
            // Lock the PO row to prevent race conditions during update
            $lockedPo = PurchaseOrder::where('id', $po->id)->lockForUpdate()->firstOrFail();

            // Double check immutability inside transaction
            if (in_array($lockedPo->status, ['RECEIVED', 'CANCELLED'])) {
                throw new InvalidArgumentException("Order is immutable once {$lockedPo->status}.");
            }

            // If transitioning to RECEIVED, increment inventory atomically
            if ($newStatus === 'RECEIVED') {
                foreach ($lockedPo->items as $item) {
                    // Lock the product row and update
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }

            $lockedPo->status = $newStatus;
            $lockedPo->save();

            return $lockedPo;
        });
    }
}
