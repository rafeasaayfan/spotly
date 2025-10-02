<?php

namespace App\Listeners\WebsiteApproved;

use App\Events\WebsiteApproved;
use App\Models\WebsitePaymentMethod;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDefaultPaymentMethod implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebsiteApproved $event): void
    {
        WebsitePaymentMethod::updateOrCreate([
            'website_id' => $event->website->id,
            'payment_method_id' => 1
        ], [
            'is_active' => 1
        ]);
    }
}
