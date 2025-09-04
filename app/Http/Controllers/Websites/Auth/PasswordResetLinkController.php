<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebsiteUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request)
    {
        return $this->inertiaRender('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
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
            'email' => 'required|email',
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

        return back()->with('status', __('A reset link will be sent if the account exists.'));
    }
}
