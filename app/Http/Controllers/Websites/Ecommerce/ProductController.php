<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\ProductRequest;
use App\Models\EcommerceCart;
use App\Models\EcommerceCartItem;
use App\Models\EcommerceProduct;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    public function index(string $slug)
    {
        $product = EcommerceProduct::where('website_id', $this->website->id)->active()->whereHas('inStockVariants')
            ->where('slug', $slug)->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name'])->firstOrFail();

        $cartItemsCount = EcommerceCartItem::query();
        if (Auth::check()) {
            $cartItemsCount = $cartItemsCount->whereHas('cart', function ($q) {
                $q->where('website_id', $this->website->id)
                    ->where('website_user_id', Auth::id());
            })->count();
        } else {
            $cartItemsCount = $cartItemsCount->whereHas('cart', function ($q) {
                $q->where('website_id', $this->website->id)
                    ->where('session_id', session()->getId());
            })->count();
        }

        return $this->inertiaRender('pages/Product', [
            'colors' => $this->websiteTemplate->templateColor,
            'iniProduct' => $product,
            'websiteNameAndLogo' => $this->websiteNameAndLogo,
            'websiteFooterData' => $this->websiteFooterData,
            'cartItemsCount' => $cartItemsCount
        ], true);
    }

    public function addToCart(ProductRequest $request)
    {
        $product = EcommerceProduct::where('website_id', $this->website->id)
            ->active()
            ->where('slug', $request->slug)->whereHas('inStockVariants')
            ->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name'])
            ->firstOrFail();

        $variant = $product->inStockVariants->firstWhere('color', $request->color);

        if ($variant) {
            if ($variant->stock_quantity < $request->quantity) {
                return $this->jsonError('Not enough stock for this variant.');
            }
        }

        try {
            // check cart
            $cart = EcommerceCart::where('website_id', $this->website->id)
                ->when(Auth::check(), function ($q) {
                    $q->where('website_user_id', Auth::id());
                }, function ($q) {
                    $q->where('session_id', session()->getId());
                })
                ->first();

            // add new
            if (!$cart) {
                $cart = EcommerceCart::create([
                    'website_id' => $this->website->id,
                    'website_user_id' => Auth::check() ? Auth::id() : null,
                    'session_id' => Auth::check() ? null : session()->getId(),
                ]);
            }

            // check items
            $item = $cart->items()
                ->where('product_id', $request->product_id)
                ->where('color', $request->color)
                ->first();

            // update quantity
            if ($item) {
                $item->increment('quantity', $request->quantity);

                // create new item
            } else {
                $cart->items()->create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'imageUrl' => $request->imageUrl ?? null,
                    'color' => $request->color,
                    'unit_price' => $request->unit_price,
                    'expires_at' => now()->addDays(2),
                ]);
            }

            // update the status
            if ($cart->status !== 'pending') {
                $cart->update(['status' => 'pending']);
            }

            $variant->decrement('stock_quantity', $request->quantity);
            $product->refresh();

            return $this->jsonSuccess('Your product was added to cart successfully', ['product' => $product]);
        } catch (\Exception $e) {
            return $this->logResponse('ProductController@addToCart', $e, 'An error occurred while add the Product to cart');
        }
    }
}
