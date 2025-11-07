<?php

namespace App\Jobs;

use App\Mail\UserStatusMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserStatusMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected string $userEmail;
    protected string $userName;
    protected string $status;
    
    /**
     * Create a new job instance.
     */
    public function __construct(string $userEmail, string $userName, string $status)
    {
        $this->userEmail = $userEmail;
        $this->userName = $userName;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->userEmail)->send(new UserStatusMail(
                $this->userName,
                $this->status,
            ));
            
        } catch(\Exception $e) {
            Log::error('Failed to send user status email', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
