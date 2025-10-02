<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceOrder extends Model
{
    protected $fillable = [
        'website_id',
        'website_user_id',
        'payment_method_id',
        'delivery_fee_id',

        'order_number',

        'subtotal',
        'discount_amount',
        'total_amount',

        'delivery_address',
        'city',

        'note',
        'cancellation_reason',

        'status_changed_at',
        'status',
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

    /**
     * Get the delivery fee for order.
    */
    public function deliveryFee()
    {
        return $this->belongsTo(DeliveryFee::class, 'delivery_fee_id');
    }

    /**
     * Get the order items.
    */
    public function items()
    {
        return $this->hasMany(EcommerceOrderItem::class, 'order_id');
    }
}
