<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProduct extends Model
{
    protected $fillable = [
        'website_id',
        'category_id',
        'brand_id',

        'name',
        'description',
        'short_description',

        'price',
        'sale_price',
        'stock_quantity',

        'weight',
        'length',
        'width',
        'height',

        'dimension_unit',
        'weight_unit',

        'colors',

        'views_count',
        'sales_count',

        'is_in_home',
        'is_special',
        'is_active',
        'is_downloadable',
    ];

    protected $casts = [
        'colors' => 'array',
        'is_in_home' => 'boolean',
        'is_special' => 'boolean',
        'is_active' => 'boolean',
        'is_downloadable' => 'boolean',
    ];

    /**
     * Get the website that the product is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get the category that the product is associated with.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the brand that the product is associated with.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
