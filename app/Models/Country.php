<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Country extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $appends = ['flag'];

    protected $fillable = [
        'country',
        'country_ar',
        'country_fr',
        'code',
        'phone_code',
        'region',
        'is_active'
    ];

    /**
     * Get the active countries.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get the flags from media.
     */
    public function getFlagAttribute()
    {
        return $this->getFirstMediaUrl('flag');
    }
}
