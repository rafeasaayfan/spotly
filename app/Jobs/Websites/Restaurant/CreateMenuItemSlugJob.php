<?php

namespace App\Jobs\Websites\Restaurant;

use App\Models\RestaurantMenuItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateMenuItemSlugJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected RestaurantMenuItem $item;

    /**
     * Create a new job instance.
     */
    public function __construct(RestaurantMenuItem $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $item = RestaurantMenuItem::find($this->item->id);

        if (!$item) {
            return;
        }

        $slug = Str::slug($item->name);

        $originalSlug = $slug;
        $counter = 1;

        while (RestaurantMenuItem::where('website_id', $item->website_id)
            ->where('id', '!=', $item->id)
            ->where('slug', $slug)
            ->exists()) 
        {
            $slug = $originalSlug . '-' . $counter++;
        }

        $item->slug = $slug;
        $item->save();
    }
}
