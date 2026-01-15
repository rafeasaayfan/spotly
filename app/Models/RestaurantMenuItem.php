<?php

namespace App\Models;

use App\Traits\HasMediaUploads;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class RestaurantMenuItem extends Model implements HasMedia
{
    use InteractsWithMedia, HasMediaUploads;

    protected $appends = ['restaurant_item_images'];

    protected $fillable = [
        'website_id',
        'category_id',

        'name',
        'slug',

        'price',
        'discount_price',

        'short_description',
        'description',

        'views_count',

        'is_discount',
        'is_in_home',
        'is_special',
        'is_active',
    ];

    /**
     *  Menu item belong to one website.
    */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     *  Menu item belong to one category.
    */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     *  Menu item has many options.
    */
    public function options()
    {
        return $this->hasMany(RestaurantMenuItemOption::class, 'menu_item_id');
    }

    /**
     * Get the restaurant_item_images from media.
     */
    public function getRestaurantItemImagesAttribute()
    {
        return $this->getMedia('restaurant_item_images');
    }

    /**
     * Scope a query to only include active menu items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include menu items shown in home.
     */
    public function scopeInHome($query)
    {
        return $query->where('is_in_home', true);
    }

    /**
     * Scope a query to only include special menu items.
     */
    public function scopeSpecial($query)
    {
        return $query->where('is_special', true);
    }
}
