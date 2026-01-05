<?php

namespace App\Actions;

use App\Models\Website;

class DeleteWebsiteData
{
    public function handle(Website $website): void
    {
        match ($website->website_type_id) {
            1 => $this->deleteEcommerceData($website),
            default     => null,
        };
    }

    // Ecommerce
    protected function deleteEcommerceData(Website $website): void
    {
        $website->ecommerceProducts()
            ->with('variants')
            ->chunkById(20, function ($products) {
                foreach ($products as $product) {
                    foreach ($product->variants as $variant) {
                        $variant->delete();
                    }
                    $product->delete();
                }
            });
    }
}
