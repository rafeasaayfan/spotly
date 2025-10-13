<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
