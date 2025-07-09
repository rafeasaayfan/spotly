<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class websitePaymentMethod extends Model
{
    protected $fillable = ['website_id', 'payment_method_id', 'settings', 'is_active'];

    /**
     * The website that uses this payment method.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * The payment method that is used by the website.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
