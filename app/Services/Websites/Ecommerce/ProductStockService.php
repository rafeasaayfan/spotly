<?php

namespace App\Services\Websites\Ecommerce;

use App\Models\EcommerceOrderItem;
use App\Models\EcommerceProduct;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductStockService
{
    protected Website $website;

    /**
     * Create a new WebsiteStatusService instance.
     *
     * @param Website $website            
     */
    public function __construct()
    {
        $this->website = app('website');
    }

    /**
     * Validate if requested quantity for a product variant is available in stock,
     * considering items in the user's cart and pending orders.
     *
     * @param EcommerceProduct $product
     * @param $cartItems
     * @param int $quantity
     * @param string $color
     * @param string $action (The action to calculate the total client qty, ATC: Add To Cart, CQ: Change Quantity)
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function validateQuantity(EcommerceProduct $product, $cartItems, int $quantity, string $color, string $action): string
    {
        try {
            $variant = $product->inStockVariants->firstWhere('color', $color);

            if (!$variant) {
                return __('messages.color_not_available');
            }

            $cartItem = $cartItems
                ->where('product_id', $product->id)
                ->where('color', $color)
                ->first();

            $pendingOrderItemQty = EcommerceOrderItem::withOrderStatus('pending')->where('product_id', $product->id)
                ->whereHas('order', function ($q) {
                    if (Auth::guard('website')->check()) {
                        $q->where('website_user_id', Auth::guard('website')->id());
                    } else {
                        $q->where('session_id', session()->getId());
                    }
                })->where('color', $variant->color)->sum('quantity');

            $available = $variant->stock_quantity - $variant->reserved_quantity;
            $requestedTotal = $action === 'ATC' ? $quantity + ($cartItem->quantity ?? 0) + ($pendingOrderItemQty ?? 0)
                : $quantity + ($pendingOrderItemQty ?? 0);

            if ($available < $requestedTotal) {
                return __('messages.not_enough_stock_variant');
            }

            return 'done';
        } catch (\Exception $e) {
            Log::error('EcommerceSyncService Error: syncCart failed', [
                'error' => $e->getMessage(),
            ]);

            return $e->getMessage();
        }
    }
}
