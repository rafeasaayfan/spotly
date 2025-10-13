<?php

namespace App\Http\Controllers\Websites\Common\Settings;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Common\Settings\ProfileUpdateRequest;
use App\Models\Country;
use App\Models\EcommerceCartItem;
use App\Models\WebsiteUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request)
    {
        $countries = Country::with('media')->active()->get();

        $phoneNumber = WebsiteUser::where('website_id', $this->website->id)
            ->where('id', $request->user('website')->id)->pluck('phone_number')->first();

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

        return $this->inertiaRender('pages/settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'countries' => $countries,
            'phone_number' => $phoneNumber,
            'colors' => $this->websiteTemplate->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo,
            'websiteFooterData' => $this->websiteFooterData,
            'cartItemsCount' => $cartItemsCount
        ], true);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user('website')->fill($request->validated());

        if ($request->user('website')->isDirty('email')) {
            $request->user('website')->email_verified_at = null;
        }

        $request->user('website')->save();

        return to_route('website.profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user('website');

        Auth::guard('website')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
