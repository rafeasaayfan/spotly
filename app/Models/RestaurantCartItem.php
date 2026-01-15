<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantCartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'option_id',

        'image_urls',

        'quantity',
        'unit_price',

        'options',
        'options_hash'
    ];

    protected $casts = [
        'image_urls' => 'array',
        'options' => 'array',
    ];

    /**
     * Get the cart that the item is associated with.
     */
    public function cart()
    {
        return $this->belongsTo(RestaurantCart::class, 'cart_id');
    }

    /**
     * Get the menu item that the cart item is associated with.
     */
    public function menuItem()
    {
        return $this->belongsTo(RestaurantMenuItem::class, 'menu_item_id');
    }

    /**
     * Get the option that the cart item is associated with.
     */
    public function option()
    {
        return $this->belongsTo(RestaurantMenuItemOption::class, 'option_id');
    }
}
