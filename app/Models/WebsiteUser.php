<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteUser extends Model
{
    protected $fillable = ['website_id', 'name', 'email', 'password'];

    /**
     * Get the website onwer.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
