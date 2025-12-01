<?php

namespace App\Models;

use App\Enums\Websites\Ecommerce\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class EcommerceOrder extends Model
{
    protected $fillable = [
        'website_id',
        'website_user_id',
        'payment_method_id',
        'delivery_fee_id',

        'session_id',
        
        'order_number',

        'subtotal',
        'total_amount',

        'phone_number',
        'city',
        'delivery_address',

        'note',
        'cancellation_reason',

        'status_changed_at',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
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
    public function user()
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

    /**
     * Get the track orders for the order.
     */
    public function trackOrder()
    {
        return $this->hasMany(EcommerceTrackOrder::class, 'order_id');
    }
}
