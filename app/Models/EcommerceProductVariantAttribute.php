<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductVariantAttribute extends Model
{
    protected $with = ['attribute', 'attributeValue', 'color'];

    protected $appends = [
        'attribute_name',
        'attribute_name_ar',
        'attribute_value_value',
        'attribute_value_value_ar',
        'color_name',
        'color_name_ar',
        'color_code'
    ];

    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'attribute_value_id',
        'attribute_value',
        'color_id',
    ];

    /**
     * Get the product variant that the attribute is associated with.
     */
    public function productVariant()
    {
        return $this->belongsTo(EcommerceProductVariant::class, 'product_variant_id');
    }

    /**
     * Get the attribute that the attribute is associated with.
     */
    public function attribute()
    {
        return $this->belongsTo(EcommerceProductAttribute::class, 'attribute_id');
    }

    /**
     * Get the attribute value that the attribute is associated with.
     */
    public function attributeValue()
    {
        return $this->belongsTo(EcommerceProductAttributeValue::class, 'attribute_value_id');
    }

    /**
     * Get the color that the attribute is associated with.
     */
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    /**
     * Get the attribute name.
     */
    public function getAttributeNameAttribute()
    {
        return $this->attribute?->name;
    }

    /**
     * Get the attribute name in Arabic.
     */
    public function getAttributeNameArAttribute()
    {
        return $this->attribute?->name_ar;
    }

    /**
     * Get the attribute value.
     */
    public function getAttributeValueValueAttribute()
    {
        return $this->attribute_value ?? $this->attributeValue?->value;
    }

    /**
     * Get the attribute value in Arabic.
     */
    public function getAttributeValueValueArAttribute()
    {
        return $this->attribute_value ?? $this->attributeValue?->value_ar;
    }

    /**
     * Get the color name.
     */
    public function getColorNameAttribute()
    {
        return $this->color?->name;
    }

    /**
     * Get the color name in Arabic.
     */
    public function getColorNameArAttribute()
    {
        return $this->color?->ar_name;
    }

    /**
     * Get the color code.
     */
    public function getColorCodeAttribute()
    {
        return $this->color?->code;
    }
}
