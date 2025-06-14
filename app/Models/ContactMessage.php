<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['website_id', 'name', 'email', 'description'];

    /**
     * The website owner for this message and if it null so its a message for spotly.
     */
    public function website()
    {
        return $this->hasOne(Website::class);
    }
}
