<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorTemplate extends Model
{
    protected $fillable = [
        'website_color_id',
        'name',
        'key',
        'description',
        'light',
        'dark',
    ];

    /**
     * Get the website color associated with this template.
     */
    public function websiteColor()
    {
        return $this->belongsTo(WebsiteColor::class);
    }
}
