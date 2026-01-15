<?php

namespace App\Models;

use App\Enums\Websites\UserRole;
use App\Enums\Websites\UserStatus;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class WebsiteUser extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $guard_name = 'website';

    protected $fillable = [
        'website_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone_number',
        'status',
        'role'
    ];

    protected $casts = [
        'status' => UserStatus::class,
        'role' => UserRole::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the website that the user is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    // ============================== Ecommerce ==============================
    /**
     * Get the cart items for the user.
     */
    public function ecommerceCart()
    {
        return $this->hasOne(EcommerceCart::class, 'website_user_id');
    }

    /**
     * Get the orders for the user.
     */
    public function ecommerceOrders()
    {
        return $this->hasMany(EcommerceOrder::class, 'website_user_id');
    }

    // ============================== Restaurant ==============================
    /**
     * Get the cart items for the user.
     */
    public function restaurantCart()
    {
        return $this->hasOne(RestaurantCart::class, 'website_user_id');
    }

    /**
     * Get the orders for the user.
     */
    public function restaurantOrders()
    {
        return $this->hasMany(RestaurantOrder::class, 'website_user_id');
    }
}
