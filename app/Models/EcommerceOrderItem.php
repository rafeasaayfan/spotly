<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceOrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',

        'quantity',
        'unit_price',
        'total_price',

        'color',
        'notes',
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
}
