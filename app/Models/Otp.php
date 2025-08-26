<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['user_id', 'website_id', 'code', 'purpose', 'attempts', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
    
    /**
     *  OTP belong to one user.
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  OTP belong to one website.
    */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     *  Check if the code is expired compared to now.
    */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     *  Check if there have been too many requests in one hour.
     */
    public function canRequest(int $maxAttempts = 3): bool
    {
        return $this->attempts < $maxAttempts || $this->updated_at->diffInHours(now()) >= 1;
    }
    
    /**
     * Get the number of seconds remaining until expiration (now - expires_at).
     */
    public function secondsUntilExpiration()
    {
        return intval(now()->diffInSeconds($this->expires_at, false));
    }
}
