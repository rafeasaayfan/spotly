<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Websites\BaseController;
use App\Models\WebsiteUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends BaseController
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request)
    {
        return $this->inertiaRender('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'colors' => $this->websiteTemplate()->templateColor
        ], true);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|lowercase|email:rfc,dns',
        ]);
    
        $website = app('website');

        $user = WebsiteUser::where('email', $request->email)
            ->where('website_id', $website->id)
            ->first();
    
        if ($user) {
            Password::broker('website_users')->sendResetLink([
                'email' => $user->email,
            ]);
        }

        return back()->with('status', __('messages.password_reset_link_sent'));
    }
}
