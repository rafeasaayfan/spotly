<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('lang') // from URL
                    ?? $request->get('lang') // from query param
                    ?? session('locale', config('app.locale'));

        if (in_array($locale, config('app.supported_locales'))) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            abort(404);
        }

        return $next($request);
    }
}
