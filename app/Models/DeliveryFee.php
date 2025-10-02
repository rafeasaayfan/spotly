<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryFee extends Model
{
    protected $fillable = [
        'website_id', 
        'city',
        'amount'
    ];

    /**
     * Get the website that the fee is associated with.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
