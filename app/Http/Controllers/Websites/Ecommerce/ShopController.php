<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\EcommerceCartItem;
use App\Models\EcommerceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends BaseController
{
    public function index(Request $request)
    {
        $categories = Category::active()->where('website_id', $this->website->id)->get();
        $brands = Brand::active()->where('website_id', $this->website->id)->get();

        $products = $this->getProducts($request);

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

        return $this->inertiaRender(
            'pages/shop/Shop',
            [
                'colors' => $this->websiteTemplate->templateColor,
                'websiteNameAndLogo' => $this->websiteNameAndLogo,
                'websiteFooterData' => $this->websiteFooterData,
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
                'cartItemsCount' => $cartItemsCount
            ],
            true
        );
    }

    protected function getProducts(Request $request)
    {
        $query = EcommerceProduct::where('website_id', $this->website->id)->active()->whereHas('inStockVariants')
        ->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name']);

        $perPage  = (int) ($request->limit ?? 12);
        $search   = trim($request->search ?? '');
        $category = $request->category ?? null;
        $brand    = $request->brand ?? null;
        $minPrice = $request->minPrice ?? 0;
        $maxPrice = $request->maxPrice ?? null;
        $special = $request->special ?? false;
        $onSale = $request->onSale ?? false;
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
    
        if ($category) {
            $categoryId = Category::active()->where('website_id', $this->website->id)
            ->where(function ($q) use ($category) {
                $q->where('name', $category)->orWhere('ar_name', $category);
            })->value('id');;

            if($categoryId) $query->where('category_id', $categoryId);
        }
    
        if ($brand) {
            $brandId = Brand::active()->where('website_id', $this->website->id)
            ->where('name', $brand)->value('id');;

            if($brandId) $query->where('brand_id', $brandId);
        }

        if ($special) {
            $query->where('is_special', true);
        }

        if ($onSale) {
            $query->whereNotNull('sale_price');
        }
    
        if ($minPrice !== null && $maxPrice !== null) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        } elseif ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
    
        return $query->paginate($perPage);
    }
}
