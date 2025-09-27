<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['website_id', 'name', 'description', 'is_active'];

    /**
     * Get the website that the brand is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get active brand.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // ============================== Ecommerce ==============================
    /**
     * Get the ecommerce products for the brand.
     */
    public function ecommerceProducts()
    {
        return $this->hasMany(EcommerceProduct::class, 'brand_id');
    }
}
