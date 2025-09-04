<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSocialAccount extends Model
{
    protected $fillable = [
        'website_user_id',
        'website_id',
        'provider',
        'provider_id',
        'token',
        'refresh_token',
    ];

    public function websiteUser()
    {
        return $this->belongsTo(WebsiteUser::class);
    }
}
