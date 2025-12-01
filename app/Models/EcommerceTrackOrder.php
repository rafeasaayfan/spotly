<?php

namespace App\Models;

use App\Enums\Websites\Ecommerce\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class EcommerceTrackOrder extends Model
{
    protected $fillable = [
        'order_id',
        'cancellation_reason',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    /**
     * Get the order that the track order is associated with.
     */
    public function order()
    {
        return $this->belongsTo(EcommerceOrder::class, 'order_id');
    }
}
