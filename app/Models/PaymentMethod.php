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
    ];

    /**
     * Get the websites that use this payment method.
     */
    public function websites()
    {
        return $this->hasMany(WebsitePaymentMethod::class, 'payment_method_id');
    }

    /**
     * Get websites where this payment method is active.
     */
    public function activeWebsites()
    {
        return $this->hasMany(WebsitePaymentMethod::class, 'payment_method_id')
                    ->where('is_active', true);
    }
}
