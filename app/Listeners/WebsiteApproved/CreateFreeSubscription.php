<?php

namespace App\Listeners\WebsiteApproved;

use App\Events\WebsiteApproved;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateFreeSubscription implements ShouldQueue
{
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
        try {
            $subscription = Subscription::firstOrCreate(
                [
                    'user_id' => $event->website->owner_id,
                    'website_id' => $event->website->id,
                    'plan_id' => 1,
                ],
                [
                    'status' => 'free_trial',
                    'start_date' => now(),
                    'end_date' => now()->addDays(3),
                ]
            );

            if($subscription->wasRecentlyCreated) {
                Payment::create([
                    'user_id' => $event->website->owner_id,
                    'website_id' => $event->website->id,
                    'plan_id' => 1,
                    'amount' => 0,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);          
            }

        } catch (\Exception $e) {
            Log::error('Failed to create the website free trial subscription', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
