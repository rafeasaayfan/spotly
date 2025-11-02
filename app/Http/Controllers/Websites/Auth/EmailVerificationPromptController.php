<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Websites\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EmailVerificationPromptController extends BaseController
{
    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user('website')->hasVerifiedEmail()
            ? $this->redirectSuccess('dashboard.index', '', forWebsite: true)
            : $this->inertiaRender('auth/VerifyEmail', [
                'status' => $request->session()->get('status'),
                'websiteNameAndLogo' => $this->websiteNameAndLogo(),
                'colors' => $this->websiteTemplate()->templateColor
            ], true);
    }
}
