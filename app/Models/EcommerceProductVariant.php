<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\HasMediaUploads;

class EcommerceProductVariant extends Model implements HasMedia
{
    use InteractsWithMedia, HasMediaUploads;

    protected $appends = ['ecommerce_product_images'];

    protected $fillable = [
        'product_id',

        'stock_quantity',
        'reserved_quantity',
        'price',
    ];

    /**
     * Get the product that the variant is associated with.
     */
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }

    /**
     * Get the attributes that the variant is associated with.
     */
    public function attributes()
    {
        return $this->hasMany(EcommerceProductVariantAttribute::class, 'product_variant_id');
    }

    /**
     * Get the ecommerce_product_images from media.
     */
    public function getEcommerceProductImagesAttribute()
    {
        return $this->getMedia('ecommerce_product_images');
    }
}
