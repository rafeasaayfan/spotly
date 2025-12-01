<?php 

namespace App\Services\Websites\Ecommerce;

use App\Models\EcommerceOrderItem;
use App\Models\EcommerceProduct;
use Illuminate\Support\Facades\Auth;

class ProductVariantQuantityService
{
    /**
     * Update the product's variants to reflect remaining stock (taking cart items into account).
     *
     * @param EcommerceProduct $product
     * @param \Illuminate\Support\Collection $cartItems
     * @return EcommerceProduct
     */
    public function updateProductQuantity(EcommerceProduct $product, $cartItems)
    {
        $pendingOrderItems = EcommerceOrderItem::withOrderStatus('pending')->where('product_id', $product->id)
            ->whereHas('order', function ($q) {
                if (Auth::guard('website')->check()) {
                    $q->where('website_user_id', Auth::guard('website')->id());
                } else {
                    $q->where('session_id', session()->getId());
                }
            })->get();

        $updatedVariants = $product->variants
            ->map(function ($variant) use ($cartItems, $pendingOrderItems) {
                $cartItemQty = optional($cartItems->firstWhere('product_variant_id', $variant->id))->quantity ?? 0;
                $pendingOrderItemQty = optional($pendingOrderItems->where('product_variant_id', $variant->id))->sum('quantity') ?? 0;

                if($variant->stock_quantity === null) {
                    $variant->display_quantity = null;

                    return $variant;
                }

                $availableQty = ($variant->stock_quantity - $variant->reserved_quantity) - ($cartItemQty + $pendingOrderItemQty);

                $variant->display_quantity = max(0, $availableQty);

                return $variant;
            })
            ->values();

        return $product->setRelation('variants', $updatedVariants);
    }
}