<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EcommerceProductVariant extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $appends = ['ecommerce_product_image'];

    protected $fillable = [
        'product_id',

        'color',
        'stock_quantity',
        'reserved_quantity',
    ];

    /**
     * Get the product that the variant is associated with.
     */
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }

    /**
     * Get the ecommerce_product_image from media.
     */
    public function getEcommerceProductImageAttribute()
    {
        return $this->getFirstMediaUrl('ecommerce_product_image');
    }
}
