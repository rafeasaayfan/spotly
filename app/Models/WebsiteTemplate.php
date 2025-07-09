<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteTemplate extends Model
{
    protected $fillable = ['website_id', 'template_id', 'template_color_id', 'is_active'];

    /**
     * Get the website that the template color is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get the template that the template color is associated with.
     */
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Get the template color that the website template color is associated with.
     */
    public function templateColor()
    {
        return $this->belongsTo(TemplateColor::class, 'template_color_id');
    }
}
