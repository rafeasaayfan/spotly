<?php

namespace App\Providers;

use App\Auth\Guards\WebsiteSessionGuard;
use App\Models\Website;
use App\Observers\WebsiteObserver;
use App\Policies\WebsitePolicy;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;
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

        // Register custom website session guard
        Auth::extend('website_session', function ($app, $name, array $config) {
            $provider = $app['auth']->createUserProvider($config['provider'] ?? null);
            
            $guard = new WebsiteSessionGuard(
                $name,
                $provider,
                $app['session.store'],
                $app['request']
            );

            // When using the "remember me" functionality of the authentication services we
            // will need to be set the encryption key of the cookie, since these cookies
            // must get encrypted. Otherwise, unencrypted cookies will be sent.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });
    }
}
