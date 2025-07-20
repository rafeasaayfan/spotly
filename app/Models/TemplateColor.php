<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'name',

        'bg_body_light',
        'bg_body_dark',
        'bg_nav_light',
        'bg_nav_dark',
        'bg_footer_light',
        'bg_footer_dark',

        'foreground_light',
        'foreground_dark',
        'foreground_active_light',
        'foreground_active_dark',
        'foreground_muted_light',
        'foreground_muted_dark',

        'bg_field_light',
        'bg_field_dark',

        'bg_card_light',
        'bg_card_hover_light',
        'bg_card_dark',
        'bg_card_hover_dark',

        'bg_content_light',
        'bg_content_hover_light',
        'bg_content_active_light',
        'bg_content_dark',
        'bg_content_hover_dark',
        'bg_content_active_dark',

        'bg_dropdown_light',
        'bg_dropdown_dark',

        'primary_light',
        'primary_hover_light',
        'primary_dark',
        'primary_hover_dark',
        'danger_light',
        'danger_hover_light',
        'danger_dark',
        'danger_hover_dark',
        'secondary_light',
        'secondary_hover_light',
        'secondary_dark',
        'secondary_hover_dark',

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
        return $this->belongsToMany(Template::class, 'template_template_colors')
                    ->withPivot('is_default')
                    ->withTimestamps()
                    ->using(TemplateTemplateColor::class);
    }

    /**
     * Get templates where this color is the default.
     */
    public function defaultForTemplates()
    {
        return $this->belongsToMany(Template::class, 'template_template_colors')
                    ->wherePivot('is_default', true)
                    ->withTimestamps()
                    ->using(TemplateTemplateColor::class);
    }
}
