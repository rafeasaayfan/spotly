<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\WebsiteSocialAccount;
use App\Models\WebsiteUser;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;

class SocialAccountsController extends Controller
{
    public function redirectToGoogle() : RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

        } catch (\Exception $e) {
            return $this->logResponse('SocialAccountsController@callbackFromGoogle', $e);
        }

        //* Check if website user exists
        $websiteUser = WebsiteUser::where('website_id', app('website')->id)->where('email', $googleUser->getEmail())->firstOrFail();
        if (!$websiteUser) {
            // Create website user if not exists
            $websiteUser = WebsiteUser::create([
                'website_id' => app('website')->id,
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str()->random(16)), // Generate random password
            ]);

            event(new Registered($websiteUser));
        }

        //* Update or create social account
        WebsiteSocialAccount::updateOrCreate(
            ['website_id' => app('website')->id, 'provider_id' => $googleUser->id, 'provider' => 'google'],
            [
                'website_user_id' => $websiteUser->id,
                'token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]
        );

        Auth::guard('website')->login($websiteUser);

        return $this->redirectSuccess('dashboard.index', '', forWebsite: true);
    }
}
