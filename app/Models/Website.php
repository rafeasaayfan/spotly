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
        'phone_number',
        'email',

        'about_us',

        'country',
        'city',
        'address',

        'instagram',
        'facebook',
        'tiktok',
        'youtube',

        'language',
        'timezone',
        'currency',

        'views_count',
        'is_active',
        'is_verified',
        'status',

        'approved_at',
        'published_at',
    ];


    /**
     * The website has owner.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The website has website type.
     */
    public function websiteType()
    {
        return $this->belongsTo(WebsiteType::class, 'website_type_id');
    }

    /**
     * The website reviewed by admin.
     */
    public function approvedOrDeniedBy()
    {
        return $this->belongsTo(User::class, 'approved_or_denied_by');
    }

    /**
     * The website payment methods.
     */
    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'website_payment_method')
                    ->withPivot('is_active', 'settings')
                    ->withTimestamps();
    }

    /**
     * The website active payment methods.
     */
    public function activePaymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'website_payment_method')
                    ->wherePivot('is_active', true)
                    ->withPivot('settings')
                    ->withTimestamps();
    }

    /**
     * The website color theme.
     */
    public function websiteTemplateColors()
    {
        return $this->hasMany(WebsiteTemplateColor::class, 'website_id');
    }

    /**
     * The website active template color.
     */
    public function websiteActiveTemplateColor()
    {
        return $this->hasMany(WebsiteTemplateColor::class, 'website_id')
                    ->where('is_active', true);
    }

    /**
     * The website contact messages.
     */
    public function websiteMessages()
    {
        return $this->hasMany(WebsiteMessage::class, 'website_id');
    }

    /**
     * The website users.
     */
    public function websiteUsers()
    {
        return $this->hasMany(WebsiteUser::class, 'website_id');
    }
}
