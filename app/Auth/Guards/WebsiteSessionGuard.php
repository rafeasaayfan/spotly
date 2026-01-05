<?php

namespace App\Auth\Guards;

use App\Models\Website;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;

class WebsiteSessionGuard extends SessionGuard
{
    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        $websiteId = $this->getWebsiteIdFromRequest();
        return 'login_website_' . $websiteId . '_' . $this->name;
    }

    /**
     * Get the name of the cookie used to store the "recall me" token.
     *
     * @return string
     */
    public function getRecallerName()
    {
        $websiteId = $this->getWebsiteIdFromRequest();
        return 'remember_website_' . $websiteId . '_' . $this->name . '_' . sha1(static::class);
    }

    /**
     * Get the website ID from the request host/subdomain.
     *
     * @return int|string
     */
    protected function getWebsiteIdFromRequest()
    {
        // First try to get from app binding (if middleware has run)
        try {
            if (app()->bound('website')) {
                $website = app('website');
                if ($website && isset($website->id)) {
                    return $website->id;
                }
            }
        } catch (\Exception $e) {
            // Continue to fallback
        }

        // Fallback: extract from request host
        if ($this->request) {
            $host = $this->request->getHost();
            
            // Skip for Spotly admin domain
            if (in_array($host, ['127.0.0.1', 'spotly.test', 'localhost'])) {
                return 'default';
            }

            $parts = explode('.', $host);
            $subdomain = $parts[0];

            if ($subdomain === 'www' && isset($parts[1])) {
                $subdomain = $parts[1];
            }

            if ($subdomain) {
                // Cache the website lookup to avoid hitting DB on every request
                $website = Cache::remember("website_session_id_{$subdomain}", 180, function () use ($subdomain) {
                    return Website::where('subdomain', $subdomain)
                        ->active()
                        ->where('status', 'approved')
                        ->first(['id']);
                });

                if ($website) {
                    return $website->id;
                }
            }
        }

        return 'default';
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        // Ensure the user belongs to the current website
        try {
            $websiteId = $this->getWebsiteIdFromRequest();
            if ($websiteId !== 'default' && isset($user->website_id) && $user->website_id != $websiteId) {
                // User doesn't belong to this website, don't authenticate
                return;
            }
        } catch (\Exception $e) {
            // Allow authentication to proceed on error
        }

        parent::setUser($user);
    }
}
