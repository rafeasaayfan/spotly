<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Websites\BaseController;
use App\Models\WebsiteUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends BaseController
{
    /**
     * Show the password reset page.
     */
    public function create(Request $request)
    {
        return $this->inertiaRender('auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'colors' => $this->websiteTemplate()->templateColor
        ], true);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|lowercase|email:rfc,dns',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $website = app('website');
        $websiteId = $website->id;

        $user = WebsiteUser::where('email', $request->email)
            ->where('website_id', $websiteId)
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['No user found for this website with this email.'],
            ]);
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('website_users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PasswordReset) {
            return to_route('website.login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
