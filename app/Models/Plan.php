<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['website_type_id', 'name', 'price', 'currency', 'duration', 'features', 'is_active'];
    
    protected $casts = [
        'features' => 'array'
    ];

    /**
     * The plan has website type.
     */
    public function websiteType()
    {
        return $this->belongsTo(WebsiteType::class, 'website_type_id');
    }

    /**
     * Get the active plan.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
