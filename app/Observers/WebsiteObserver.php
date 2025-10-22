<?php

namespace App\Observers;

use App\Models\Website;
use Illuminate\Support\Facades\Cache;

class WebsiteObserver
{
    /**
     * Handle the Website "created" event.
     */
    public function created(Website $website): void
    {
        //
    }

    /**
     * Handle the Website "updated" event.
     */
    public function updated(Website $website): void
    {
        Cache::forget("website_{$website->subdomain}");
    }

    /**
     * Handle the Website "deleted" event.
     */
    public function deleted(Website $website): void
    {
        Cache::forget("website_{$website->subdomain}");
    }

    /**
     * Handle the Website "restored" event.
     */
    public function restored(Website $website): void
    {
        //
    }

    /**
     * Handle the Website "force deleted" event.
     */
    public function forceDeleted(Website $website): void
    {
        //
    }
}
