<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $website = app('website');

        $websiteType = $website->websiteType?->type;
        $websiteTemplate = $website->activeWebsiteTemplate?->template?->name;

        return $request->user('website')->hasVerifiedEmail()
            ? $this->redirectSuccess('dashboard.index', '', forWebsite: true)
            : $this->inertiaRender('auth/VerifyEmail', [
                'status' => $request->session()->get('status'),
            ], true);
    }
}
