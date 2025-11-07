<?php

namespace App\Http\Controllers\Websites\Common\Settings;

use App\Http\Controllers\Websites\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteAccountController extends BaseController
{
    /**
     * Show the user's delete account settings page.
     */
    public function index()
    {
        return $this->inertiaRender('pages/settings/Delete', [
            'colors' => $this->websiteTemplate()->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'cartItemsCount' => $this->cartItems()?->count()
        ], true);
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
