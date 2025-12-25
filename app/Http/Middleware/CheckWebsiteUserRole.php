<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckWebsiteUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $website = app('website');
        $user = Auth::guard('website')->user()?->refresh();
        $type = $website->websiteType?->type;

        if (! in_array($role, ['admin', 'owner'])) {
            abort(403, 'Invalid role.');
        }
    
        if (! $user) {
            return redirect()->route('website.login');
        }

        if ($user->website_id !== $website->id) {
            abort(403, 'User does not belong to this website.');
        }

        if ($role === 'admin' && ($user->role->value !== 'admin' && $user->role->value !== 'owner')) {
            return redirect()->route("website.$type.home");
        }
    
        if ($role === 'owner' && ($user->id !== $website->owner_id || $user->role->value !== 'owner')) {
            return redirect()->back()->with([
                'success' => false,
                'toastType' => 'error',
                'message' => 'You are not authorized to perform this action'
            ]);
        }

        return $next($request);
    }
}
