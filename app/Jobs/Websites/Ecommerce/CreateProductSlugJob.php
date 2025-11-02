<?php

namespace App\Jobs\Websites\Ecommerce;

use App\Models\EcommerceProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateProductSlugJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected EcommerceProduct $product;

    /**
     * Create a new job instance.
     */
    public function __construct(EcommerceProduct $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = EcommerceProduct::find($this->product->id);

        if (!$product) {
            return;
        }

        $slug = Str::slug($product->name);

        $originalSlug = $slug;
        $counter = 1;

        while (EcommerceProduct::where('website_id', $product->website_id)
            ->where('id', '!=', $product->id)
            ->where('slug', $slug)
            ->exists()) 
        {
            $slug = $originalSlug . '-' . $counter++;
        }

        $product->slug = $slug;
        $product->save();
    }
}
