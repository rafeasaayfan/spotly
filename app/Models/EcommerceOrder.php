<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceOrder extends Model
{
    protected $fillable = [
        'website_id',
        'website_user_id',
        'payment_method_id',

        'order_number',

        'subtotal',
        'delivery_amount',
        'total_amount',

        'delivery_address',
        'city',

        'paid_at',
        'delivered_at',
        'confirmed_at',
        'cancelled_at',
        'refunded_at',
        'cancellation_reason',

        'ip_address',
        'user_agent',

        'status',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * Get the website that the order is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * Get the website user that the order is associated with.
     */
    public function websiteUser()
    {
        return $this->belongsTo(WebsiteUser::class, 'website_user_id');
    }

    /**
     * Get the payment method for the order.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
