<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantMenuItemOption extends Model
{
    protected $fillable = [
        'menu_item_id',
        'option_group_id',

        'name',
        'name_ar',

        'price_delta',
        'is_increase',

        'option_explain',

        'is_active',
    ];

    /**
     *  Option belong to one menu item.
    */
    public function menuItem()
    {
        return $this->belongsTo(RestaurantMenuItem::class, 'menu_item_id');
    }

    /**
     *  Option belong to one menu item group.
    */
    public function group()
    {
        return $this->belongsTo(RestaurantMenuItemGroup::class, 'option_group_id');
    }
}
