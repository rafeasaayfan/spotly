<?php

namespace App\Http\Middleware;

use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class IdentifyWebsite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        $subdomain = $parts[0];

        if ($host === '127.0.0.1' || $host === 'spotly.test' || $host === 'localhost') {
            return $next($request);
        }

        if ($subdomain === 'www') {
            $subdomain = isset($parts[1]) ? $parts[1] : null;
        }

        if (!$subdomain) {
            abort(404, 'Website not found');
        }

        $website = Cache::remember("website_{$subdomain}", 60, function () use ($subdomain) {
            return Website::where('subdomain', $subdomain)
                ->active()->status('approved')
                ->with(['websiteType:id,type', 'activeWebsiteTemplate.template:id,name'])
                ->first();
        });

        if (!$website) {
            abort(404, 'Website not found');
        }

        app()->instance('website', $website);

        return $next($request);
    }
}
