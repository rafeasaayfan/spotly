<?php

namespace App\Http\Controllers\Websites\Common\Settings;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceCartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends BaseController
{
    /**
     * Show the user's password settings page.
     */
    public function edit()
    {
        $cartItemsCount = EcommerceCartItem::query();
        if (Auth::check()) {
            $cartItemsCount = $cartItemsCount->whereHas('cart', function ($q) {
                $q->where('website_id', $this->website->id)
                    ->where('website_user_id', Auth::id());
            })->count();
        } else {
            $cartItemsCount = $cartItemsCount->whereHas('cart', function ($q) {
                $q->where('website_id', $this->website->id)
                    ->where('session_id', session()->getId());
            })->count();
        }

        return $this->inertiaRender('pages/settings/Password', [
            'colors' => $this->websiteTemplate->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo,
            'websiteFooterData' => $this->websiteFooterData,
            'cartItemsCount' => $cartItemsCount
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
