<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        $googleUser = Socialite::driver('google')->user();

        //* Check if user exists
        $user = User::where('email', $googleUser->getEmail())->first();
        if (!$user) {
            // Create user if not exists
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str()->random(16)), // Generate random password
            ]);

            event(new Registered($user));
        }

        //* Update or create social account
        SocialAccount::updateOrCreate(
            ['provider_id' => $googleUser->id],
            [
                'user_id' => $user->id,
                'provider' => 'google',
                'token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]
        );


        Auth::login($user);

        return redirect()->intended(route('landing', absolute: false));
    }
}
