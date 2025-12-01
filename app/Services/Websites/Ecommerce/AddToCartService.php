<?php

namespace App\Services\Websites\Ecommerce;

use App\Models\EcommerceCart;
use App\Models\EcommerceProduct;
use App\Models\EcommerceProductVariant;
use App\Services\Websites\Ecommerce\ProductStockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddToCartService
{
    protected int $websiteId;

    public function __construct(int $websiteId)
    {
        $this->websiteId = $websiteId;
    }

    public function addToCart(array $data, $cartItems)
    {
        try {
            $product = $this->getProduct($data['product_id']);

            $selectedVariant = $this->getVariant($product, $data['variantId']);

            $validateVariant = $this->validateVariant($selectedVariant, $product, $cartItems, $data);
            if ($validateVariant !== 'done') {
                return [
                    'success' => false,
                    'message' => $validateVariant,
                    'data' => null,
                ];
            }

            $cart = $this->getCart();

            $this->createOrUpdateCartItem($cart, $product, $data, $selectedVariant);

            // Set cart status to pending if not already
            if ($cart->status !== 'pending') {
                $cart->update(['status' => 'pending']);
            }

            $cartItems = $cart->items();

            $productService = new ProductService($this->websiteId);
            $product = $productService->getProductBySlug($product->slug, $cartItems);

            return [
                'success' => true,
                'message' => __('messages.product_added_to_cart'),
                'data' => [
                    'product' => $product,
                    'cartItemsCount' => $cart->items()->count()
                ],
            ];
        } catch (\Exception $e) {
            Log::error('AddToCartService@addToCart Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the product by id.
     */
    protected function getProduct(int $productId): EcommerceProduct
    {
        return EcommerceProduct::where('website_id', $this->websiteId)
            ->active()
            ->where('id', $productId)
            ->with(['variants.attributes', 'category:id,name,ar_name', 'brand:id,name'])
            ->firstOrFail();
    }

    /**
     * Get the variant by id.
     */
    protected function getVariant(EcommerceProduct $product, int $variantId): EcommerceProductVariant | null
    {
        return $product->variants->firstWhere('id', $variantId);
    }

    /**
     * Validate the variant.
     */
    protected function validateVariant(EcommerceProductVariant $variant, EcommerceProduct $product, $cartItems, array $data)
    {
        if ($variant && isset($variant->stock_quantity) && $variant->stock_quantity !== null) {
            return ProductStockService::validateQuantity($product, $cartItems, $data['quantity'], $data['variantId'], 'ATC');
        }

        return 'done';
    }

    /**
     * Get the cart.
     */
    protected function getCart(): EcommerceCart
    {
        $cart = EcommerceCart::where('website_id', $this->websiteId)
            ->where('website_user_id', Auth::guard('website')->check() ? Auth::guard('website')->id() : null)
            ->where('session_id', Auth::guard('website')->check() ? null : session()->getId())
            ->first();

        if (!$cart) {
            $cart = EcommerceCart::create([
                'website_id' => $this->websiteId,
                'website_user_id' => Auth::guard('website')->check() ? Auth::guard('website')->id() : null,
                'session_id' => Auth::guard('website')->check() ? null : session()->getId(),
            ]);
        }

        return $cart;
    }

    /**
     * Create or update the cart item.
     */
    protected function createOrUpdateCartItem(EcommerceCart $cart, EcommerceProduct $product, array $data, EcommerceProductVariant $variant)
    {
        $oldQuantity = $cart->items()->where('product_id', $product->id)->where('product_variant_id', $variant->id)->first()?->quantity ?? 0;

        $attributesWithValues = collect($variant->attributes)->map(fn($attr) => [
            'attribute_name' => $attr->attribute_name,
            'attribute_name_ar' => $attr->attribute_name_ar,
            'attribute_value_name' => $attr->attribute_value_value,
            'attribute_value_name_ar' => $attr->attribute_value_value_ar,
            'color_name' => $attr->color_name,
            'color_name_ar' => $attr->color_name_ar,
            'color_code' => $attr->color_code,
        ])->toArray();

        $attributesHash = md5(json_encode($attributesWithValues));

        $cart->items()->updateOrCreate(
            [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'attributes_hash' => $attributesHash,
            ],
            [
                'quantity' => $oldQuantity + $data['quantity'],
                'image_urls' => $variant->ecommerce_product_images->map(fn($image) => $image->original_url)->toArray(),
                'unit_price' => $this->handleCartItemUnitPrice($variant, $product),
                'attributes' => $attributesWithValues,
            ]
        );
    }

    /**
     * Handle the cart item unit price.
     */
    protected function handleCartItemUnitPrice(EcommerceProductVariant $variant, EcommerceProduct $product): float
    {
        if($product->is_discount && $product->discount_price) {
            if($variant->price) {
                return $variant->price - $product->discount_price;
            }

            return $product->price - $product->discount_price;
        }

        return $variant->price ?? $product->price;
    }
}
