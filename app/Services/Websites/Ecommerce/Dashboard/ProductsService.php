<?php

namespace App\Services\Websites\Ecommerce\Dashboard;

use App\Models\EcommerceProduct;
use App\Models\EcommerceProductVariant;
use App\Models\EcommerceProductVariantAttribute;

class ProductsService
{
    /**
     * Store the product variants.
     *
     * @param EcommerceProduct $product
     * @param array $variants
     * @return void
     */
    public static function storeProductVariants(EcommerceProduct $product, array $variants)
    {
        try {
            foreach ($variants as $variant) {
                $productVariant = EcommerceProductVariant::create([
                    'product_id'      => $product->id,
                    'stock_quantity'  => $variant['stock_quantity'] ?? null,
                    'price'           => $variant['price'] ?? null,
                ]);

                // Images
                $productVariant->storeMediaImages($variant['ecommerce_product_images'], 'ecommerce_product_images');

                // Attributes
                if (!empty($variant['attributes'])) {
                    self::storeProductVariantAttributes($productVariant->id, $variant['attributes']);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('ProductsController@storeProductVariants: An error occurred while storing the product variants', 0, $e);
        }
    }

    /**
     * Handle the create, update, and deletion of product variants.
     *
     * @param EcommerceProduct $product
     * @param array $variants
     * @return void
     */
    public static function syncProductVariants(EcommerceProduct $product, array $variants)
    {
        try {
            // Delete variants that are not in the request
            $requestVariantIds = collect($variants)->pluck('id')->filter()->all();
            EcommerceProductVariant::where('product_id', $product->id)->whereNotIn('id', $requestVariantIds)
                ->chunk(10, function ($variants) {
                    $variants->each->delete();
                });

            // Create or Update variants
            foreach ($variants as $variant) {
                $productVariant = EcommerceProductVariant::updateOrCreate([
                    'id' => $variant['id'] ?? null,
                    'product_id' => $product->id,
                ], [
                    'stock_quantity' => $variant['stock_quantity'] ?? null,
                    'price' => $variant['price'] ?? null,
                ]);

                // Handle Images
                $productVariant->updateMediaImages($variant['ecommerce_product_images'], 'ecommerce_product_images', false);

                // Handle Attributes
                if (!empty($variant['attributes'])) {
                    self::storeProductVariantAttributes($productVariant->id, $variant['attributes']);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred while syncing the product variants: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Store the product variant attributes.
     *
     * @param int $productVariantId
     * @param array $attributes
     * @return void
     */
    protected static function storeProductVariantAttributes(int $productVariantId, array $attributes)
    {
        try {
            $payload = [];

            foreach ($attributes as $attribute) {
                if (empty($attribute['value'])) {
                    continue; // Skip attributes without values
                }

                $attribute_value_id = null;
                $attribute_value = null;
                if ($attribute['name'] !== 'color') {
                    if ($attribute['type'] === 'text') {
                        $attribute_value = $attribute['value'];

                    } elseif ($attribute['type'] === 'select') {
                        $attribute_value_id = $attribute['value'];
                    }
                }

                $payload[] = [
                    'product_variant_id' => $productVariantId,
                    'attribute_id'       => $attribute['id'],
                    'color_id'           => $attribute['name'] === 'color' ? $attribute['value'] : null,
                    'attribute_value_id' => $attribute_value_id,
                    'attribute_value'    => $attribute_value,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }

            if (!empty($payload)) {
                EcommerceProductVariantAttribute::upsert(
                    $payload,
                    ['product_variant_id', 'attribute_id'],
                    ['color_id', 'attribute_value_id', 'attribute_value', 'updated_at']
                );
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred while storing the product variant attributes: ' . $e->getMessage(), 0, $e);
        }
    }
}
