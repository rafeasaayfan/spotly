<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductAttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'color_id',
        'value',
    ];
    
    /**
     * Get the attribute that the value is associated with.
     */
    public function attribute()
    {
        return $this->belongsTo(EcommerceProductAttribute::class, 'attribute_id');
    }

    /**
     * Get the color that the value is associated with.
     */
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}
