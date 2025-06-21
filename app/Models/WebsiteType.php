<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteType extends Model
{
    protected $fillable = ['created_by', 'type', 'is_active'];

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
     * Get the color templates associated to this website type.
     */
    public function colorTemplates()
    {
        return $this->hasMany(Website::class);
    }
}
