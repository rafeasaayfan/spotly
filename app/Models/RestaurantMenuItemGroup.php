<?php

namespace App\Models;

use App\Enums\Websites\Restaurant\MenuItemGroupPriceType;
use Illuminate\Database\Eloquent\Model;

class RestaurantMenuItemGroup extends Model
{
    protected $fillable = [
        'name',
        'name_ar',

        'is_required_for_admin',
        'is_required_for_client',

        'max_select',

        'price_type',

        'is_active'
    ];

    protected $casts = [
        'price_type' => MenuItemGroupPriceType::class
    ];

    /**
     * Get active groups.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
