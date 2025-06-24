<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Website extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'owner_id',
        'website_type_id',
        'approved_or_denied_by',
        'name',
        'subdomain',
        'description',
        'country',
        'city',
        'address',
        'phone_number',
        'instagram',
        'facebook',
        'tiktok',
        'language',
        'views_count',
        'is_active',
        'status',
    ];


    /**
     * The website has onwe.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The website has type.
     */
    public function websiteType()
    {
        return $this->belongsTo(WebsiteType::class);
    }

    /**
     * The website reviewed by.
     */
    public function viewedBy()
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
