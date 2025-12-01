<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductAttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'value_ar',
        'is_active',
    ];
    
    /**
     * Get the attribute that the value is associated with.
     */
    public function attribute()
    {
        return $this->belongsTo(EcommerceProductAttribute::class, 'attribute_id');
    }
}
