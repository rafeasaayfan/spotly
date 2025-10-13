<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceCartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{
    public function index()
    {
        $cartItems = EcommerceCartItem::query();
        if (Auth::check()) {
            $cartItems = $cartItems->whereHas('cart', function ($q) {
                $q->where('website_id', $this->website->id)
                    ->where('website_user_id', Auth::id());
            });
        } else {
            $cartItems = $cartItems->whereHas('cart', function ($q) {
                $q->where('website_id', $this->website->id)
                    ->where('session_id', session()->getId());
            });
        }
        $cartItems = $cartItems->with(['cart', 'product:id,name,slug']);

        return $this->inertiaRender('pages/Cart', [
            'iniItems' => $cartItems->get(),
            'colors' => $this->websiteTemplate->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo,
            'websiteFooterData' => $this->websiteFooterData,
            'cartItemsCount' => $cartItems->count()
        ], true);
    }

    public function removeItem(Request $request)
    {
        try {
            $cartItem = EcommerceCartItem::query();
            if (Auth::check()) {
                $cartItem = $cartItem->whereHas('cart', function ($q) {
                    $q->where('website_id', $this->website->id)
                        ->where('website_user_id', Auth::id());
                });
            } else {
                $cartItem = $cartItem->whereHas('cart', function ($q) {
                    $q->where('website_id', $this->website->id)
                        ->where('session_id', session()->getId());
                });
            }

            $cartItemForDelete = $cartItem->where('id', $request->itemId)->first();

            if (! $cartItemForDelete) {
                return $this->jsonError('Item not found in your cart');
            }
    
            $cartItemForDelete->delete();

            return $this->jsonSuccess('Item removed from your cart successfully', [
                'items' => $cartItem->with(['cart', 'product:id,name,slug'])->get(),
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('CartController@removeItem', $e, 'An error occurred while deleting the item from cart');
        }
    }
}
