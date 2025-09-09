<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'website_id',
        'plan_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'start_date' => 'datetime',
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
     * Get status.
    */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
