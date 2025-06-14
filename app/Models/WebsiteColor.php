<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteColor extends Model
{
    protected $fillable = ['created_by', 'website_type_id', 'website_id'];

    /**
     * Get the user that created the website color.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the website type associated with this color.
     */
    public function websiteType()
    {
        return $this->belongsToMany(WebsiteType::class);
    }

    /**
     * Get the website associated with this color.
     */
    public function website()
    {
        return $this->hasOne(Website::class);
    }

    /**
     * Get the website colors template.
     */
    public function colors()
    {
        return $this->hasMany(ColorTemplate::class);
    }
}
