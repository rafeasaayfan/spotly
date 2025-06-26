<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteTemplate extends Model
{
    protected $fillable = ['created_by', 'website_type_id', 'is_custom'];

    /**
     * Get the user that created the website color.
     */
    public function createdBy()
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
        return $this->hasMany(Website::class);
    }

    /**
     * Get the website colors template.
     */
    public function colors()
    {
        return $this->hasMany(TemplateColor::class);
    }
}
