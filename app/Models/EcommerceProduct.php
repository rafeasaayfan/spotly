<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProduct extends Model
{
    protected $appends = ['sales_count'];

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
     * The relationship between the product and order items.
     */
    public function orderItems()
    {
        return $this->hasMany(EcommerceOrderItem::class, 'product_id');
    }

    /**
     * Get the product variants.
     */
    public function variants()
    {
        return $this->hasMany(EcommerceProductVariant::class, 'product_id');
    }

    /**
     * Get the product variants that are in stock.
     */
    public function inStockVariants()
    {
        return $this->hasMany(EcommerceProductVariant::class, 'product_id')
            ->whereRaw('stock_quantity - reserved_quantity > 0');
    }

    /**
     * Get the product stock quantity.
     */
    public function scopeWithStockQuantity($query)
    {
        return $query->withSum('variants', 'stock_quantity');
    }

    /**
     * Get the product reserved quantity.
     */
    public function scopeWithReservedQuantity($query)
    {
        return $query->withSum('variants', 'reserved_quantity');
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products shown in home.
     */
    public function scopeInHome($query)
    {
        return $query->where('is_in_home', true);
    }

    /**
     * Scope a query to only include special products.
     */
    public function scopeSpecial($query)
    {
        return $query->where('is_special', true);
    }

    /**
     * Get the number of sales for the product (only delivered orders).
     */
    public function getSalesCountAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered');
            })
            ->sum('quantity');
    }
}
