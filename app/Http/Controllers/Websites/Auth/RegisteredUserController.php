<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Auth\RegisterRequest;
use App\Models\WebsiteUser;
use App\Services\Websites\Ecommerce\EcommerceSyncService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends BaseController
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return $this->inertiaRender('auth/Register', [
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'colors' => $this->websiteTemplate()->templateColor
        ], true);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $website = app('website');
        $guestSessionId = session()->getId(); 

        $websiteUser = WebsiteUser::create([
            'website_id' => $website->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($websiteUser));

        Auth::guard('website')->login($websiteUser);

        match ($this->website->websiteType->type) {
            'e-commerce' => EcommerceSyncService::sync($guestSessionId),
            default => null,
        };

        return $this->redirectSuccess('dashboard.index', '', forWebsite: true);
    }
}
