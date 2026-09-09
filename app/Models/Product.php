<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku', 'name', 'description', 'unit_price', 'stock_quantity', 'low_stock_threshold'
    ];

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
