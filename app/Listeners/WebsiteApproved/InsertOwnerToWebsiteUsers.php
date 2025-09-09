<?php

namespace App\Listeners\WebsiteApproved;

use App\Events\WebsiteApproved;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\WebsiteSocialAccount;
use App\Models\WebsiteUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InsertOwnerToWebsiteUsers implements ShouldQueue
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
            $user = User::findOrFail($event->website->owner_id);
            $userSocial = SocialAccount::where('user_id', $event->website->owner_id)->first();

            $websiteUser = WebsiteUser::firstOrCreate(
                [
                    'website_id' => $event->website->id,
                    'email' => $user->email,
                    'role' => 'owner',
                ],
                [
                    'name' => $user->name,
                    'email_verified_at' => now(),
                    'password' => $userSocial ? Hash::make(Str::random(16)) : $user->password,
                    'phone_number' => $user->phone_number,
                    'status' => 'active',
                ]
            );

            if ($userSocial) {
                WebsiteSocialAccount::firstOrCreate(
                    [
                        'website_user_id' => $websiteUser->id,
                        'website_id' => $event->website->id,
                        'provider' => $userSocial->provider,
                    ],
                    [
                        'provider_id' => $userSocial->provider_id,
                        'token' => $userSocial->token,
                        'refresh_token' => $userSocial->refresh_token,
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to create the website owner in the website user', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
