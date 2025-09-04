<?php

namespace App\Jobs;

use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteSocialAccount;
use App\Models\WebsiteUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InsertWebsiteOnwerToWebsiteUsers implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected Website $website;

    /**
     * Create a new job instance.
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = User::findOrFail($this->website->owner_id);
            $userSocial = SocialAccount::where('user_id', $this->website->owner_id)->first();

            $websiteUser = WebsiteUser::updateOrCreate(['website_id' => $this->website->id, 'email' => $user->email], [
                'name' => $user->name,
                'email_verified_at' => now(),
                'password' => $userSocial ? Hash::make(Str::random(16)) : $user->password,
                'phone_number' => $user->phone_number,
                'status' => 'active',
                'role' => 'owner',
            ]);

            if($userSocial) {
                WebsiteSocialAccount::updateOrCreate(['website_user_id' => $websiteUser->id, 'website_id' => $this->website->id], [
                    'provider' => $userSocial->provider,
                    'provider_id' => $userSocial->provider_id,
                    'token' => $userSocial->token,
                    'refresh_token' => $userSocial->refresh_token
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to create the website onwer in the website user', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
