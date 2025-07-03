<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['created_by', 'website_type_id', 'name', 'description', 'is_active'];

    /**
     * Get the user that created the template.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the website type associated with this template.
     */
    public function websiteType()
    {
        return $this->belongsTo(WebsiteType::class, 'website_type_id');
    }

    /**
     * Get the template colors associated with this template.
     */
    public function templateColors()
    {
        return $this->belongsToMany(TemplateColor::class, 'template_template_color')
                    ->withPivot('is_default')
                    ->withTimestamps();
    }

    /**
     * Get the default template color for this template.
     */
    public function defaultTemplateColor()
    {
        return $this->belongsToMany(TemplateColor::class, 'template_template_color')
                    ->wherePivot('is_default', true)
                    ->withTimestamps();
    }
}
