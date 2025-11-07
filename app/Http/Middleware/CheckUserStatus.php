<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard()->user()?->refresh();

        if (!$user) {
            return $next($request);
        }

        if ($user->status === 'banned') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with([
                'success' => false,
                'toastType' => 'error',
                'message' => __('messages.account_banned')
            ]);
        }

        if ($user->status === 'inactive') {
            if (
                $request->is('website-builder*') ||
                (
                    !$request->is('dashboard') && $request->is('dashboard*') &&
                    !$request->is('dashboard/make-payment*') && !$request->is('dashboard/my-payment*') &&
                    !$request->is('dashboard/my-websites*') && !$request->is('dashboard/my-website*')
                )
            ) {
                return redirect()->route("landing")->with([
                    'success' => false,
                    'toastType' => 'error',
                    'message' => __('messages.account_inactive')
                ]);
            }
        }

        return $next($request);
    }
}
