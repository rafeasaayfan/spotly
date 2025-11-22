<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\CheckoutRequest;
use App\Jobs\Websites\Ecommerce\SendOrderEmailJob;
use App\Models\Country;
use App\Models\EcommerceCart;
use App\Models\EcommerceCartItem;
use App\Models\EcommerceOrder;
use App\Models\EcommerceProduct;
use App\Models\WebsiteUser;
use App\Services\Websites\Ecommerce\ProductStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends BaseController
{
    /**
     * Display the cart detail page.
     */
    public function index()
    {
        $countries = Country::with('media')->active()->get();
        $cities = config('cities.lebanon');

        return $this->inertiaRender('pages/Cart', [
            'iniItems' => $this->cartItems()->with(['cart', 'product:id,name,slug'])->get(),
            'colors' => $this->websiteTemplate()->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'iniCartItemsCount' => $this->cartItems()->count(),

            'countries' => $countries,
            'cities' => $cities
        ], true);
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(Request $request)
    {
        $validated = $request->validate([
            'itemId' => 'required|integer',
        ]);

        try {
            $cartItemForDelete = $this->cartItems()->where('id', $validated['itemId'])->first();
            if (! $cartItemForDelete) {
                return $this->jsonError(__('messages.item_cart_not_found'));
            }

            $cartId = $cartItemForDelete->cart_id;

            $cartItemForDelete->delete();

            $items = EcommerceCartItem::where('cart_id', $cartId)->with(['cart', 'product:id,name,slug'])->get();

            return $this->jsonSuccess(__('messages.item_cart_removed'), [
                'items' => $items,
                'cartItemsCount' => $items->count(),
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('CartController@removeItem', $e, 'An error occurred while deleting the item from cart');
        }
    }

    /**
     * Change the quantity of an item in the cart.
     */
    public function changeQuantity(Request $request)
    {
        $validated = $request->validate([
            'itemId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $cartItem = $this->cartItems()?->where('id', $validated['itemId'])->first();
            if (! $cartItem) {
                return $this->jsonError(__('messages.item_cart_not_found'));
            }
            $cartId = $cartItem->cart_id;
            $productId = $cartItem->product_id;

            $product = EcommerceProduct::where('website_id', $this->website->id)->active()
                ->where('id', $productId)
                ->whereHas('inStockVariants')
                ->firstOrFail();

            $validateQuantity = ProductStockService::validateQuantity($product, $this->cartItems(), $validated['quantity'], $cartItem->color_id, 'CQ');
            if ($validateQuantity !== 'done') {
                return $this->jsonError($validateQuantity);
            }

            $cartItem->update([
                'quantity' => $validated['quantity'],
            ]);

            $items = EcommerceCartItem::where('cart_id', $cartId)->with(['cart', 'product:id,name,slug'])->get();

            return $this->jsonSuccess(__('messages.qty_updated'), ['items' => $items]);
        } catch (\Exception $e) {
            return $this->logResponse('CartController@removeItem', $e, 'An error occurred while deleting the item from cart');
        }
    }

    /**
     * Handle the checkout process for selected items in the cart.
     */
    public function checkout(CheckoutRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get the items
            $cartItemIds = collect($request->input('items'))->pluck('id');
            $checkedCartItems = EcommerceCartItem::whereIn('id', $cartItemIds)->get();

            if ($checkedCartItems->isEmpty()) {
                return $this->jsonError(__('messages.no_cart_item'));
            }

            // Make the order
            $subtotal = $checkedCartItems->sum(fn($item) => $item->unit_price * $item->quantity);
            $totalAmount = $subtotal;

            $order = EcommerceOrder::create([
                'website_id' => $this->website->id,
                'website_user_id' => Auth::guard('website')->check() ? Auth::guard('website')->id() : null,
                'payment_method_id' => 1,
                'session_id' => Auth::guard('website')->check() ? null : session()->getId(),
                'order_number' => sprintf(
                    '%s-%s-%s-%s',
                    strtoupper($this->website->name),
                    now()->format('ymd'), 
                    now()->format('His'),
                    Str::upper(Str::random(2))
                ),
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'delivery_address' => $request->delivery_address,
                'note' => $request->note,
            ]);

            // Make the order items
            foreach ($checkedCartItems as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'imageUrl' => $cartItem->imageUrl,
                    'color' => $cartItem->color,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                ]);
            }

            // Delete the checked cart items
            EcommerceCartItem::whereIn('id', $cartItemIds)->delete();
            $cartItems = EcommerceCartItem::where('cart_id', $checkedCartItems[0]->cart_id)->with(['cart', 'product:id,name,slug'])->get();

            if ($cartItems->count() <= 0) {
                EcommerceCart::where('id', $checkedCartItems[0]->cart_id)->where('website_id', $this->website->id)
                    ->update([
                        'status' => 'checked_out'
                    ]);
            }

            if (Auth::guard('website')->check()) {
                WebsiteUser::where('id', Auth::guard('website')->id())
                    ->whereNull('phone_number')
                    ->update(['phone_number' => $request->phone_number]);
            }

            DB::commit();

            SendOrderEmailJob::dispatch(
                $order->order_number,
                'new_order',
                $this->website->id,
                $this->website->name,
                $this->website->email,
                $this->website->subdomain,
                Auth::guard('website')->check() ? Auth::guard('website')->user()->email : null
            )->afterCommit();

            return $this->jsonSuccess(__('messages.order_placed'), [
                'items' => $cartItems,
                'cartItemsCount' => $cartItems->count(),
                'cartCheckedOut' => $cartItems->count() > 0 ? false : true
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('CartController@removeItem', $e, 'An error occurred while deleting the item from cart');
        }
    }
}
