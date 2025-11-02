<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Auth\LoginRequest;
use App\Services\Websites\Ecommerce\EcommerceSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthenticatedSessionController extends BaseController
{
    /**
     * Show the login page.
     */
    public function create(Request $request)
    {
        return $this->inertiaRender('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'colors' => $this->websiteTemplate()->templateColor
        ], true);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $guestSessionId = session()->getId();

        $request->authenticate();

        EcommerceSyncService::sync($guestSessionId);

        $request->session()->regenerate();

        return $this->redirectSuccess('dashboard.index', '', forWebsite: true);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('website')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
