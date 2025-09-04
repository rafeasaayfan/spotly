<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Websites\Auth\RegisterRequest;
use App\Models\WebsiteUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return $this->inertiaRender('auth/Register', [], true);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $website = app('website');

        $websiteUser = WebsiteUser::create([
            'website_id' => $website->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($websiteUser));

        Auth::guard('website')->login($websiteUser);

        return $this->redirectSuccess('dashboard.index', '', forWebsite: true);
    }
}
