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
        $subdomain = explode('.', $host)[0];
    
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
        $request->merge(['website' => $website]);

        return $next($request);
    }
}
