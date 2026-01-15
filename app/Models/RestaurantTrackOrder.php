<?php

namespace App\Models;

use App\Enums\Websites\Restaurant\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class RestaurantTrackOrder extends Model
{
    protected $fillable = [
        'website_id',
        'order_id',
        'user_id',

        'status_reason',
        'status',

        'is_session_order'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    /**
     * Get the order that the track order is associated with.
     */
    public function order()
    {
        return $this->belongsTo(RestaurantOrder::class, 'order_id');
    }

    /**
     * Get the user that the track order is associated with.
     */
    public function user()
    {
        return $this->belongsTo(WebsiteUser::class, 'user_id');
    }
}
