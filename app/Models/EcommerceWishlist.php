<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceWishlist extends Model
{
    protected $fillable = [
        'website_id',
        'website_user_id',
        'product_id',

        'color',
        'notes',
    ];

    /**
     * The website that the wishlist item belongs to.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * The product that the wishlist item belongs to.
     */
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }

    /**
     * The user that the wishlist item belongs to.
     */
    public function websiteUser()
    {
        return $this->belongsTo(WebsiteUser::class, 'website_user_id');
    }
}
