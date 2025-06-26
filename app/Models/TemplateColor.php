<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateColor extends Model
{
    protected $fillable = [
        'website_template_id',
        'name',
        'key',
        'description',
        'light',
        'dark',
    ];

    /**
     * Get the website color associated with this template.
     */
    public function websiteTemplate()
    {
        return $this->belongsTo(WebsiteTemplate::class);
    }
}
