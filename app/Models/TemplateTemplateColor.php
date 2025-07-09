<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateTemplateColor extends Model
{
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
}
