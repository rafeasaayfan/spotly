<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteType extends Model
{
    protected $fillable = ['created_by', 'type', 'description', 'is_active'];

    /**
     * Get the user that created the website type.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the websites associated with this type.
     */
    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    /**
     * Get the active website types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
