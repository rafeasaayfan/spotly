<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleWebsiteRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $website = app('website');
        $user = auth('website')->user();
        $type = $website->websiteType?->type;

        if (! in_array($role, ['admin', 'owner'])) {
            abort(403, 'Invalid role.');
        }
    
        if (! $user) {
            return  redirect()->route('website.login');
        }
    
        if ($role === 'admin' && $user->role !== 'admin') {
            return redirect()->route("website.$type.home");
        }
    
        if ($role === 'owner' && ($user->id !== $website->owner_id || $user->role !== 'owner')) {
            return redirect()->route("website.$type.home");
        }

        return $next($request);
    }
}
