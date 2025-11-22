<?php

namespace App\Jobs\Spotly;

use App\Mail\Spotly\WebsiteCreationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebsiteCreationMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected int $ownerId;
    protected string $websiteName;

    /**
     * Create a new job instance.
     */
    public function __construct($ownerId, $websiteName)
    {
        $this->ownerId = $ownerId;
        $this->websiteName = $websiteName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    { 
        try {
            $owner = User::findOrFail($this->ownerId);
            $admins = User::role(['super_admin', 'admin'])->pluck('email')->toArray();
    
            foreach ($admins as $email) {
                Mail::to($email)->send(new WebsiteCreationMail(
                    'admin',
                    $this->websiteName,
                    $owner->email,
                ));
            }
    
            Mail::to($owner->email)->send(new WebsiteCreationMail(
                'owner',
                $this->websiteName,
            ));
            
        } catch(\Exception $e) {
            Log::error('Failed to send website creation emails', [
                'owner_id' => $this->ownerId,
                'website_name' => $this->websiteName,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    } 
}
