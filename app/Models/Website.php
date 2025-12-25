<?php

namespace App\Models;

use App\Enums\Spotly\WebsiteStatus;
use App\Traits\HasMediaUploads;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Website extends Model implements HasMedia
{
    use InteractsWithMedia, HasMediaUploads;

    protected $appends = ['light_logo', 'dark_logo'];

    protected $fillable = [
        'owner_id',
        'website_type_id',
        'approved_or_denied_by',

        'name',
        'subdomain',
        'phone_number',
        'email',

        'about_us',
        'about_us_ar',

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
        'status',
    ];

    protected $casts = [
        'status' => WebsiteStatus::class,
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
     * The website subscription.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'website_id');
    }
    /**
     * Scope a query to only include websites with expired subscriptions.
     */
    public function scopeExpiredSubscription($query)
    {
        return $query->withWhereHas('subscription', function ($q) {
            $q->expired();
        });
    }
    /**
     * Scope a query to only include websites with subscriptions expiring within the next given number of days.
     */
    public function scopeExpiringSubscription($query, $days = 3)
    {
        return $query->withWhereHas('subscription', function ($q) use ($days) {
            $q->expiringSoon($days);
        });
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
        return $this->hasMany(WebsitePaymentMethod::class, 'website_id');
    }

    /**
     * The website active payment methods.
     */
    public function activePaymentMethods()
    {
        return $this->hasMany(WebsitePaymentMethod::class, 'website_id')
            ->where('is_active', true);
    }

    /**
     * The website color theme.
     */
    public function websiteTemplates()
    {
        return $this->hasMany(WebsiteTemplate::class, 'website_id');
    }

    /**
     * The website active template color.
     */
    public function activeWebsiteTemplate()
    {
        return $this->hasOne(WebsiteTemplate::class, 'website_id')->where('is_active', true);
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

    /**
     * The website active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * The website status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the light logo from media.
     */
    public function getLightLogoAttribute()
    {
        return $this->getFirstMediaUrl('light_logo');
    }

    /**
     * Get the dark logo from media.
     */
    public function getDarkLogoAttribute()
    {
        return $this->getFirstMediaUrl('dark_logo');
    }


    // =================  Ecommerce    ===================================
    /**
     * Get the orders for the website.
     */
    public function ecommerceOrders()
    {
        return $this->hasMany(EcommerceOrder::class, 'website_id');
    }
}
