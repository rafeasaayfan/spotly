<?php

namespace App\Jobs\Spotly;

use App\Mail\Spotly\SendExpiredSoonSubscriptionMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class ExpiredSoonSubscriptionMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected Collection $expiringSoonSubscriptions;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $expiringSoonSubscriptions)
    {
        $this->expiringSoonSubscriptions = $expiringSoonSubscriptions;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            foreach ($this->expiringSoonSubscriptions as $subscription) {
                Mail::to($subscription->website->owner->email)->send(new SendExpiredSoonSubscriptionMail(
                    $subscription->website->name,
                    $subscription->website->owner->name,
                    getTimeWithDaysAndHours($subscription->end_date),
                ));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send expired soon subscription emails', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
