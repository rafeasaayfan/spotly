<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceOrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',

        'imageUrl',
        'color',

        'quantity',
        'unit_price',
    ];

    /**
     * Get the order that the item is associated with.
     */
    public function order()
    {
        return $this->belongsTo(EcommerceOrder::class, 'order_id');
    }

    /**
     * Get the product that the item is associated with.
     */
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }

    /**
     * Scope a query to only include items whose order has a given status.
     */
    public function scopeWithOrderStatus($query, $status)
    {
        return $query->whereHas('order', function ($q) use ($status) {
            $q->where('status', $status);
        });
    }
}
