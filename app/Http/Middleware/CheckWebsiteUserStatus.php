<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckWebsiteUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $website = app('website');
        $user = Auth::guard('website')->user()?->refresh();
        $type = $website->websiteType?->type;

        if (!$user) {
            return $next($request);
        }

        if ($user->status === 'banned') {
            auth('website')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('website.login')->with([
                'success' => false,
                'toastType' => 'error',
                'message' => __('messages.account_banned')
            ]);
        }

        if ($user->status === 'inactive') {
            if ($request->is('dashboard*') || $request->is('checkout*') || $request->is('cart*') || $request->is('product*')) {
                return redirect()->route("website.$type.home")->with([
                    'success' => false,
                    'toastType' => 'error',
                    'message' => __('messages.account_inactive')
                ]);
            }
        }

        return $next($request);
    }
}
