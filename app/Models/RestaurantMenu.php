<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantMenu extends Model
{
    protected $fillable = [
        'website_id',

        'name',
        'name_ar'
    ];

    /**
     * Get the website that the menu is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
