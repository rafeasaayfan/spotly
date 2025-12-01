<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductAttribute extends Model
{
    protected $fillable = [
        'website_id',
        'name',
        'name_ar',
        
        'type',
        'description',
        'is_active',
    ];

    /**
     * Get the website that the attribute is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get the values for the attribute.
     */
    public function values()
    {
        return $this->hasMany(EcommerceProductAttributeValue::class, 'attribute_id')->orderBy('id', 'asc');
    }

    /**
     * Get active attributes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
    