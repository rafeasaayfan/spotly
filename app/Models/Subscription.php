<?php

namespace App\Models;

use App\Enums\Spotly\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'website_id',
        'plan_id',
        'payment_method_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'start_date' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];

    /**
     * has one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * has one website.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * has one plan.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * has one payment method.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(paymentMethod::class);
    }

    /**
     * Get status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include subscriptions expiring within the next given number of days.
     */
    public function scopeExpiringSoon($query, $days = 3)
    {
        return $query->whereBetween('end_date', [
            now(),
            now()->addDays($days)
        ]);
    }

    /**
     * Scope a query to only include expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->whereDate('end_date', '<', now());
    }
}
