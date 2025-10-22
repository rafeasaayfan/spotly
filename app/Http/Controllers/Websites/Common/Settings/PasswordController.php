<?php

namespace App\Http\Controllers\Websites\Common\Settings;

use App\Http\Controllers\Websites\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends BaseController
{
    /**
     * Show the user's password settings page.
     */
    public function edit()
    {
        return $this->inertiaRender('pages/settings/Password', [
            'colors' => $this->websiteTemplate()->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'cartItemsCount' => $this->cartItems()?->count()
        ], true);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user('website')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
