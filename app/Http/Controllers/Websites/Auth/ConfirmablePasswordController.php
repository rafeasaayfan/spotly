<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Websites\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends BaseController
{
    /**
     * Show the confirm password page.
     */
    public function show()
    {
        return $this->inertiaRender('auth/ConfirmPassword', [
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'colors' => $this->websiteTemplate()->templateColor
        ], true);
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('website')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return $this->redirectSuccess('dashboard.index', '', forWebsite: true);
    }
}
