<?php

namespace App\Models;

use App\Enums\Spotly\PaymentCurrency;
use App\Enums\Spotly\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'website_id',
        'plan_id',
        'payment_method_id',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'details',
        'paid_at',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'currency' => PaymentCurrency::class,
    ];

    /**
     * Get the user associated with the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the website associated with the payment.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get the plan associated with the payment.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the payment method associated with the payment.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
