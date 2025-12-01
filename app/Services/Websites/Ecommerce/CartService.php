<?php

namespace App\Services\Websites\Ecommerce;

use App\Enums\Websites\Ecommerce\CartStatus;
use App\Jobs\Websites\Ecommerce\SendOrderEmailJob;
use App\Models\EcommerceCart;
use App\Models\EcommerceCartItem;
use App\Models\EcommerceOrder;
use App\Models\EcommerceProduct;
use App\Models\EcommerceTrackOrder;
use App\Models\WebsiteUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartService
{
    /**
     * Remove an item from the cart.
     *
     * @param int $itemId
     * @param \Illuminate\Support\Collection $cartItems
     * @return array
     */
    public static function removeItem(int $itemId, $cartItems)
    {
        $cartItemForDelete = $cartItems->where('id', $itemId)->first();
        if (! $cartItemForDelete) {
            return 'error';
        }

        $cartId = $cartItemForDelete->cart_id;

        $cartItemForDelete->delete();

        $items = EcommerceCartItem::where('cart_id', $cartId)->with(['cart', 'product:id,name,slug'])->get();

        return [
            'items' => $items,
            'cartItemsCount' => $items->count(),
        ];
    }

    /**
     * Change the quantity of an item in the cart.
     *
     * @param array $validated
     * @param \Illuminate\Support\Collection $cartItems
     * @param int $websiteId
     * @return array
     */
    public static function changeQuantity(array $validated, $cartItems, int $websiteId)
    {
        $cartItem = $cartItems?->where('id', $validated['itemId'])->first();
        if (! $cartItem) {
            return __('messages.item_cart_not_found');
        }
        $cartId = $cartItem->cart_id;
        $productId = $cartItem->product_id;
        $variantId = $cartItem->product_variant_id;

        $product = EcommerceProduct::where('website_id', $websiteId)->active()
            ->where('id', $productId)
            ->whereHas('variants', function ($query) use ($variantId) {
                $query->where('id', $variantId);
            })->with('variants')->first();
        if (! $product) {
            return __('messages.product_with_variant_not_found');
        }

        $variant = $product->variants->firstWhere('id', $variantId);

        if ($variant->stock_quantity !== null) {
            $validateQuantity = ProductStockService::validateQuantity($product, $cartItems, $validated['quantity'], $variantId, 'CQ');
            if ($validateQuantity !== 'done') {
                return $validateQuantity;
            }
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        $items = EcommerceCartItem::where('cart_id', $cartId)->with(['cart', 'product:id,name,slug'])->get();

        return ['items' => $items];
    }

    /**
     * Checkout the cart.
     *
     * @param array $validated
     * @param \Illuminate\Support\Collection $cartItems
     * @param int $websiteId
     * @param string $websiteName
     * @param string $websiteEmail
     * @param string $websiteSubdomain
     * @return array
     */
    public static function checkout(array $validated, $cartItems, int $websiteId, string $websiteName, string $websiteEmail, string $websiteSubdomain)
    {
        DB::beginTransaction();

        // Get the items
        $cartItemIds = collect($validated['items'])->pluck('id');
        $checkedCartItems = EcommerceCartItem::whereIn('id', $cartItemIds)->get();
        if ($checkedCartItems->isEmpty()) {
            return __('messages.no_cart_item');
        }

        // Make the order
        $order = self::createOrder($checkedCartItems, $websiteId, $websiteName, $validated);

        // Delete the checked cart items
        EcommerceCartItem::whereIn('id', $cartItemIds)->delete();
        $cartItems = EcommerceCartItem::where('cart_id', $checkedCartItems[0]->cart_id)->with(['cart', 'product:id,name,slug'])->get();

        if ($cartItems->count() <= 0) {
            self::updateCartStatus($checkedCartItems[0]->cart_id, $websiteId);
        }

        if (Auth::guard('website')->check()) {
            self::updateWebsiteUserPhoneNumber($validated['phone_number']);
        }

        DB::commit();

        SendOrderEmailJob::dispatch(
            $order->order_number,
            'new_order',
            $websiteId,
            $websiteName,
            $websiteEmail,
            $websiteSubdomain,
            Auth::guard('website')->check() ? Auth::guard('website')->user()->email : null
        )->afterCommit();

        return [
            'items' => $cartItems,
            'cartItemsCount' => $cartItems->count(),
            'cartCheckedOut' => $cartItems->count() > 0 ? false : true
        ];
    }

    /**
     * Create an order.
     *
     * @param \Illuminate\Support\Collection $checkedCartItems
     * @param int $websiteId
     * @param string $websiteName
     * @param array $validated
     * @return \App\Models\EcommerceOrder
     */
    protected static function createOrder($checkedCartItems, int $websiteId, string $websiteName, array $validated)
    {
        $subtotal = $checkedCartItems->sum(fn($item) => $item->unit_price * $item->quantity);
        $totalAmount = $subtotal;

        $order = EcommerceOrder::create([
            'website_id' => $websiteId,
            'website_user_id' => Auth::guard('website')->check() ? Auth::guard('website')->id() : null,
            'payment_method_id' => 1,
            'session_id' => Auth::guard('website')->check() ? null : session()->getId(),
            'order_number' => sprintf(
                '%s-%s-%s-%s',
                strtoupper($websiteName),
                now()->format('ymd'),
                now()->format('His'),
                Str::upper(Str::random(2))
            ),
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
            'phone_number' => $validated['phone_number'],
            'city' => $validated['city'],
            'delivery_address' => $validated['delivery_address'],
            'note' => $validated['note'],
        ]);

        // Make the order items
        foreach ($checkedCartItems as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'product_variant_id' => $cartItem->product_variant_id,
                'image_urls' => $cartItem->image_urls,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'attributes' => $cartItem->attributes,
            ]);
        }

        // Make the order tracking
        EcommerceTrackOrder::create([
            'order_id' => $order->id,
        ]);

        return $order;
    }

    /**
     * Update the cart status.
     *
     * @param int $cartId
     * @param int $websiteId
     * @return void
     */
    protected static function updateCartStatus(int $cartId, int $websiteId)
    {
        EcommerceCart::where('id', $cartId)->where('website_id', $websiteId)
            ->update([
                'status' => CartStatus::CHECKED_OUT->value
            ]);
    }

    /**
     * Update the website user phone number.
     *
     * @param string $phoneNumber
     * @return void
     */
    protected static function updateWebsiteUserPhoneNumber(string $phoneNumber)
    {
        WebsiteUser::where('id', Auth::guard('website')->id())
            ->whereNull('phone_number')
            ->update(['phone_number' => $phoneNumber]);
    }
}
