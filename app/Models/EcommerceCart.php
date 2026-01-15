<?php

namespace App\Models;

use App\Enums\Websites\Ecommerce\CartStatus;
use Illuminate\Database\Eloquent\Model;

class EcommerceCart extends Model
{
    protected $fillable = [
        'website_id',
        'website_user_id',

        'session_id',

        'status'
    ];

    protected $casts = [
        'status' => CartStatus::class,
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
    public function user()
    {
        return $this->belongsTo(WebsiteUser::class, 'website_user_id');
    }

    /**
     * Get the cart items that the cart is associated with.
     */
    public function items()
    {
        return $this->hasMany(EcommerceCartItem::class, 'cart_id');
    }

    /**
     * Get the count of items that the cart is associated with.
     */
    public function getItemsCountAttribute()
    {
        return $this->items()->count();
    }
}
