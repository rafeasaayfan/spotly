<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Website extends Model implements HasMedia
{
    use InteractsWithMedia;

    //

    /**
     * The website has onwe.
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The website has type.
     */
    public function type()
    {
        return $this->belongsTo(WebsiteType::class);
    }

    /**
     * The website reviewed by.
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'approved_or_denied_by');
    }

    /**
     * The website color theme.
     */
    public function colorTemplate()
    {
        return $this->hasOne(WebsiteColor::class);
    }

    /**
     * The website contact messages.
     */
    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }

    /**
     * The website users.
     */
    public function users()
    {
        return $this->hasMany(WebsiteUser::class);
    }
}
