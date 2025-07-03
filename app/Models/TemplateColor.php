<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',

        'bg_body_light',
        'bg_body_dark',
        'bg_nav_light',
        'bg_nav_dark',
        'bg_footer_light',
        'bg_footer_dark',

        'bg_field_light',
        'bg_field_dark',

        'bg_card_light',
        'bg_card_dark',

        'forground_light',
        'forground_dark',
        'forground_active_light',
        'forground_active_dark',
        'forground_muted_light',
        'forground_muted_dark',

        'primary_light',
        'primary_dark',
        'danger_light',
        'danger_dark',
        'secondary_light',
        'secondary_dark',

        'border_color_light',
        'border_color_dark',

        'description',

        'is_custom',
        'is_active',
    ];

    /**
     * Get the user that created the template color.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the templates that use this template color.
     */
    public function templates()
    {
        return $this->belongsToMany(Template::class, 'template_template_color')
                    ->withPivot('is_default')
                    ->withTimestamps();
    }

    /**
     * Get templates where this color is the default.
     */
    public function defaultForTemplates()
    {
        return $this->belongsToMany(Template::class, 'template_template_color')
                    ->wherePivot('is_default', true)
                    ->withTimestamps();
    }
}
