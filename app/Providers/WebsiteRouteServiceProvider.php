<?php

namespace App\Providers;

use App\Http\Middleware\IdentifyWebsite;
use App\Models\Website;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Spotly\LandingController;
use App\Http\Controllers\Spotly\WebsiteBuilderController;
use App\Http\Controllers\Spotly\WebsitePreviewController;
use App\Http\Middleware\HandleLanguage;
use App\Http\Middleware\LoadWebsiteRoutes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class WebsiteRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $host = request()->getHost();
        $parts = explode('.', $host);
        $subdomain = $parts[0];

        app()->instance('subdomain', $subdomain);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Configure the rate limiters for the application.
     */
    // protected function configureRateLimiting(): void
    // {
    //     RateLimiter::for('api', function (Request $request) {
    //         return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    //     });
    // }
}
