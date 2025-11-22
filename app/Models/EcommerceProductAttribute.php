<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductAttribute extends Model
{
    protected $fillable = [
        'website_id',
        'name',
        'type',
        'values',
        'description',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'values' => 'array',
    ];

    /**
     * Get the website that the attribute is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
    