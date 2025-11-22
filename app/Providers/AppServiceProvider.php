<?php

namespace App\Providers;

use App\Models\Website;
use App\Observers\WebsiteObserver;
use App\Policies\WebsitePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Website::class, WebsitePolicy::class);
        Website::observe(WebsiteObserver::class);

        require_once app_path('Helpers/helpers.php');
    }
}
