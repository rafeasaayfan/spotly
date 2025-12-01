<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\CheckoutRequest;
use App\Models\Country;
use App\Services\Websites\Ecommerce\CartService;
use Illuminate\Http\Request;

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
            $result = CartService::removeItem($validated['itemId'], $this->cartItems());
            if ($result === 'error') {
                return $this->jsonError(__('messages.item_cart_not_found'));
            }

            return $this->jsonSuccess(__('messages.item_cart_removed'), $result);
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
            $result = CartService::changeQuantity($validated, $this->cartItems(), $this->website->id);
            if (is_string($result)) {
                return $this->jsonError($result);
            }

            return $this->jsonSuccess(__('messages.qty_updated'), $result);
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
            $result = CartService::checkout(
                $request->validated(),
                $this->cartItems(),
                $this->website->id,
                $this->website->name,
                $this->website->email,
                $this->website->subdomain
            );
            if (is_string($result)) {
                return $this->jsonError($result);
            }

            return $this->jsonSuccess(__('messages.order_placed'), $result);
        } catch (\Exception $e) {
            return $this->logResponse('CartController@removeItem', $e, 'An error occurred while deleting the item from cart');
        }
    }
}
