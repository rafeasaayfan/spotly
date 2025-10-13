<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'website_id',
        'parent_id',
        'name',
        'ar_name',
        'description',
        'is_in_home',
        'is_active',
    ];

    /**
     * Get the website that the category is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the children categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get home category.
     */
    public function scopeInHome($query)
    {
        return $query->where('is_in_home', 1);
    }
    
    /**
     * Get active category.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Recursively get all descendant category IDs for this category.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allChildrenIds()
    {
        $ids = collect();

        foreach ($this->children as $child) {
            $ids->push($child->id);
            $ids = $ids->merge($child->allChildrenIds());
        }

        return $ids;
    }

    // ============================== Ecommerce ==============================
    /**
     * Get the ecommerce products for the category.
     */
    public function ecommerceProducts()
    {
        return $this->hasMany(EcommerceProduct::class, 'category_id');
    }
}
