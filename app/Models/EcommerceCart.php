<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceCart extends Model
{
    protected $fillable = [
        'website_id',
        'website_user_id',
        'product_id',

        'session_id',

        'quantity',
        'unit_price',
        'total_price',

        'color',

        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the website that the cart is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get the website user that the cart is associated with.
     */
    public function websiteUser()
    {
        return $this->belongsTo(WebsiteUser::class, 'website_user_id');
    }

    /**
     * Get the product that the cart is associated with.
     */
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }
}
