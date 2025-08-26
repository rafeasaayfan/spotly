<?php

namespace App\Jobs;

use App\Mail\WebsiteStatusMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebsiteStatusMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected int $ownerId;
    protected string $websiteName;
    protected string $websiteSubdomain;
    protected string $key;
    protected int $status;

    /**
     * Create a new job instance.
     */
    public function __construct($ownerId, $websiteName, $websiteSubdomain, $key, $status)
    {
        $this->ownerId = $ownerId;
        $this->websiteName = $websiteName;
        $this->websiteSubdomain = $websiteSubdomain;
        $this->key = $key;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $owner = User::findOrFail($this->ownerId);

            Mail::to($owner->email)->send(new WebsiteStatusMail(
                $owner->name,
                $this->websiteName,
                $this->websiteSubdomain,
                $this->key,
                $this->status,
            ));
            
        } catch(\Exception $e) {
            Log::error('Failed to send website creation emails', [
                'owner_id' => $this->ownerId,
                'website_name' => $this->websiteName,
                'website_subdomain' => $this->websiteSubdomain,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
