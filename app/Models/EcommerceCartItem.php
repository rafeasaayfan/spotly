<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceCartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',

        'quantity',
        'unit_price',

        'imageUrl',
        'color',

        'expires_at'
    ];

    /**
     * Get the cart that the item is associated with.
     */
    public function cart()
    {
        return $this->belongsTo(EcommerceCart::class, 'cart_id');
    }

    /**
     * Get the product that the cart item is associated with.
     */
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }
}
