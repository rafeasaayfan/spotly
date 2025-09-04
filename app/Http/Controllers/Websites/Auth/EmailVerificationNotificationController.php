<?php

namespace App\Http\Controllers\Websites\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user('website')->hasVerifiedEmail()) {
            return $this->redirectSuccess('dashboard.index', '', forWebsite: true);
        }

        $request->user('website')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
