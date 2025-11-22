<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\ProductRequest;
use App\Models\EcommerceCart;
use App\Models\EcommerceOrderItem;
use App\Models\EcommerceProduct;
use App\Services\Websites\Ecommerce\ProductStockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{
    /**
     * Display the product detail page.
     */
    public function index(string $slug)
    {
        $product = EcommerceProduct::where('website_id', $this->website->id)->active()->whereHas('inStockVariants')
            ->where('slug', $slug)->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name'])->firstOrFail();

        $cartItems = $this->cartItems()?->where('product_id', $product->id)->get();

        $iniProduct = $this->updateProductQuantity($product, $cartItems);

        $product->increment('views_count');

        return $this->inertiaRender('pages/Product', [
            'colors' => $this->websiteTemplate()->templateColor,
            'iniProduct' => $iniProduct,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'iniCartItemsCount' => $this->cartItems()?->count()
        ], true);
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart(ProductRequest $request)
    {
        try {
            $product = EcommerceProduct::where('website_id', $this->website->id)
                ->active()
                ->where('id', $request->product_id)
                ->whereHas('inStockVariants')
                ->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name'])
                ->firstOrFail();

            $validateQuantity = ProductStockService::validateQuantity($product, $this->cartItems(), $request->quantity, $request->color_id, 'ATC');
            if($validateQuantity !== 'done') {
                return $this->jsonError($validateQuantity);
            }

            // Get or create the cart for the current user/session
            $cart = EcommerceCart::where('website_id', $this->website->id)
                ->when(Auth::guard('website')->check(), function ($q) {
                    $q->where('website_user_id', Auth::guard('website')->id());
                }, function ($q) {
                    $q->where('session_id', session()->getId());
                })
                ->first();
            
            // Create a cart if not exists
            if (!$cart) {
                $cart = EcommerceCart::create([
                    'website_id' => $this->website->id,
                    'website_user_id' => Auth::guard('website')->check() ? Auth::guard('website')->id() : null,
                    'session_id' => Auth::guard('website')->check() ? null : session()->getId(),
                ]);
            }

            // Find cart item with this product/color
            $item = $cart->items()
                ->where('product_id', $product->id)
                ->where('color_id', $request->color_id)
                ->first();

            // Increment quantity or create new cart item as needed
            if ($item) {
                $item->increment('quantity', $request->quantity);

                // create new item
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'imageUrl' => $request->imageUrl ?? null,
                    'color_id' => $request->color_id,
                    'unit_price' => $request->unit_price,
                ]);
            }

            // Set cart status to pending if not already
            if ($cart->status !== 'pending') {
                $cart->update(['status' => 'pending']);
            }

            // Load cart items for this product
            $cartItemsForProduct = $cart->items()
                ->where('product_id', $product->id)
                ->get();

            $product = $this->updateProductQuantity($product, $cartItemsForProduct);

            return $this->jsonSuccess(
                __('messages.product_added_to_cart'),
                [
                    'product' => $product,
                    'cartItemsCount' => $cartItemsForProduct->count()
                ]
            );
        } catch (\Exception $e) {
            Log::error('ProductController@addToCart Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->logResponse('ProductController@addToCart', $e, $e->getTraceAsString());
        }
    }

    /**
     * Update the product's variants to reflect remaining stock (taking cart items into account).
     *
     * @param EcommerceProduct $product
     * @param \Illuminate\Support\Collection $cartItems
     * @return EcommerceProduct
     */
    protected function updateProductQuantity(EcommerceProduct $product, $cartItems)
    {
        $pendingOrderItems = EcommerceOrderItem::withOrderStatus('pending')->where('product_id', $product->id)
            ->whereHas('order', function ($q) {
                if (Auth::guard('website')->check()) {
                    $q->where('website_user_id', Auth::guard('website')->id());
                } else {
                    $q->where('session_id', session()->getId());
                }
            })->get();

        $updatedVariants = $product->inStockVariants->map(function ($variant) use ($cartItems, $pendingOrderItems) {
            $cartItemQty = optional($cartItems->firstWhere('color_id', $variant->color_id))->quantity ?? 0;
            $pendingOrderItemQty = optional($pendingOrderItems->where('color_id', $variant->color_id))->sum('quantity') ?? 0;

            $availableQty = ($variant->stock_quantity - $variant->reserved_quantity) - ($cartItemQty + $pendingOrderItemQty);

            $variant->display_quantity = max(0, $availableQty);

            return $variant;
        })
            ->filter(fn($variant) => $variant->display_quantity > 0)
            ->values();

        return $product->setRelation('inStockVariants', $updatedVariants);
    }
}
