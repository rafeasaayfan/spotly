<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TemplateTemplateColor extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $appends = ['uiImages'];

    protected $fillable = [
        'website_type_id',
        'template_id',
        'template_color_id',
        'is_active',
    ];

    /**
     * Get the website type associated with this template template color.
     */
    public function websiteType()
    {
        return $this->belongsTo(WebsiteType::class, 'website_type_id');
    }

    /**
     * Get the template associated with this template template color.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the template color associated with this template template color.
     */
    public function templateColor()
    {
        return $this->belongsTo(TemplateColor::class);
    }

    /**
     * Get the uiImages from media.
     */
    public function getUiImagesAttribute()
    {
        return $this->getMedia('uiImages');
    }
}
