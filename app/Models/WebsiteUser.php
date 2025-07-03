<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteUser extends Model
{
    protected $fillable = [
        'website_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone_number',
        'status'
    ];

    /**
     * Get the website that the user is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    // ============================== Ecommerce ==============================
    /**
     * Get the cart items for the user.
     */
    public function ecommerceCart()
    {
        return $this->hasMany(EcommerceCart::class, 'website_user_id');
    }

    /**
     * Get the wishlist items for the user.
     */
    public function ecommerceWishlist()
    {
        return $this->hasMany(EcommerceWishlist::class, 'website_user_id');
    }

    /**
     * Get the orders for the user.
     */
    public function ecommerceOrders()
    {
        return $this->hasMany(EcommerceOrder::class, 'website_user_id');
    }
}
