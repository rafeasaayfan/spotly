<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceCartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',

        'image_urls',

        'quantity',
        'unit_price',

        'attributes',
        'attributes_hash',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'attributes' => 'array',
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

    /**
     * Get the product variant that the cart item is associated with.
     */
    public function productVariant()
    {
        return $this->belongsTo(EcommerceProductVariant::class, 'product_variant_id');
    }

}
