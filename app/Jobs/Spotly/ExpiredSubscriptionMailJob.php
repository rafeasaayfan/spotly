<?php

namespace App\Jobs\Spotly;

use App\Mail\Spotly\SendExpiredSubscriptionMail;
use App\Models\User;
use App\Models\Website;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ExpiredSubscriptionMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected Collection $expiredSubscriptionIds;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $expiredSubscriptionIds)
    {
        $this->expiredSubscriptionIds = $expiredSubscriptionIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $websites = Website::whereHas('subscription', function ($q) {
                $q->whereIn('id', $this->expiredSubscriptionIds);
            })->with(['owner:id,email,name', 'subscription:website_id,end_date'])->select('id', 'owner_id', 'name', 'email')->get();

            $admins = User::role(['super_admin', 'admin'])->pluck('email')->toArray();

            foreach ($websites as $website) {
                foreach ($admins as $email) {
                    Mail::to($email)->send(new SendExpiredSubscriptionMail(
                        $website->name,
                        $website->owner->name,
                        $website->owner->email,
                        $website->subscription->end_date->format('d M Y'),
                        true
                    ));
                }

                Mail::to($website->owner->email)->send(new SendExpiredSubscriptionMail(
                    $website->name,
                    $website->owner->name,
                    $website->owner->email,
                    $website->subscription->end_date->format('d M Y'),
                    false
                ));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send expired subscription emails', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
