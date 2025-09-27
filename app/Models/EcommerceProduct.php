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
        'slug',

        'price',
        'sale_price',

        'short_description',
        'description',

        'views_count',
        'sales_count',

        'is_in_home',
        'is_special',
        'is_active',
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

    /**
     * Get the product variants.
     */
    public function variants()
    {
        return $this->hasMany(EcommerceProductVariant::class, 'product_id');
    }

    /**
     * Get the product stock quantity.
     */
    public function scopeWithStockQuantity($query)
    {
        return $query->withSum('variants', 'stock_quantity');
    }
}
