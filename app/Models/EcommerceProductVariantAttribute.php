<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductVariantAttribute extends Model
{
    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
    ];
    
    /**
     * Get the product variant that the attribute is associated with.
     */
    public function productVariant()
    {
        return $this->belongsTo(EcommerceProductVariant::class, 'product_variant_id');
    }

    /**
     * Get the attribute value that the attribute is associated with.
     */
    public function attributeValue()
    {
        return $this->belongsTo(EcommerceProductAttributeValue::class, 'attribute_value_id');
    }
}
