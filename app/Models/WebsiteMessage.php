<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteMessage extends Model
{
    protected $fillable = ['website_id', 'name', 'email', 'subject', 'status', 'type', 'message'];

    /**
     * The website that the message is for.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
