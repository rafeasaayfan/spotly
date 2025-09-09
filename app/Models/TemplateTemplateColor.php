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
        'template_id',
        'template_color_id',
        'is_default',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function templateColor()
    {
        return $this->belongsTo(TemplateColor::class);
    }

    // /**
    //  * Register media collections for the pivot table
    //  */
    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('images')
    //         ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
    //         ->withResponsiveImages();
    // }

    /**
     * Get the uiImages from media.
     */
    public function getUiImagesAttribute()
    {
        return $this->getMedia('uiImages');
    }
}
