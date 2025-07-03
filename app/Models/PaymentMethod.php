<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'settings',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the websites that use this payment method.
     */
    public function websites()
    {
        return $this->belongsToMany(Website::class, 'website_payment_method')
                    ->withPivot('is_active', 'settings')
                    ->withTimestamps();
    }

    /**
     * Get websites where this payment method is active.
     */
    public function activeWebsites()
    {
        return $this->belongsToMany(Website::class, 'website_payment_method')
                    ->wherePivot('is_active', true)
                    ->withPivot('settings')
                    ->withTimestamps();
    }
}
