<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\EcommerceProduct;
use Illuminate\Http\Request;

class ShopController extends BaseController
{
    /**
     * Display the shop detail page.
     */
    public function index(Request $request)
    {
        $categories = Category::active()->where('website_id', $this->website->id)->get();
        $brands = Brand::active()->where('website_id', $this->website->id)->get();

        $products = $this->getProducts($request);

        return $this->inertiaRender(
            'pages/shop/Shop',
            [
                'colors' => $this->websiteTemplate()->templateColor,
                'websiteNameAndLogo' => $this->websiteNameAndLogo(),
                'websiteFooterData' => $this->websiteFooterData(),
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
                'cartItemsCount' => $this->cartItems()?->count()
            ],
            true
        );
    }

    /**
     * Get paginated products for the shop based on filters (search, category, brand, price, etc).
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getProducts(Request $request)
    {
        $query = EcommerceProduct::where('website_id', $this->website->id)->active()->whereHas('inStockVariants')
            ->with(['category:id,name,ar_name', 'brand:id,name', 'variants.color']);

        $validated = $request->validate([
            'limit'     => 'nullable|integer|min:1|max:100',
            'search'    => 'nullable|string|max:150',
            'category'  => 'nullable|string|max:100',
            'brand'     => 'nullable|string|max:100',
            'minPrice'  => 'nullable|numeric|min:0',
            'maxPrice'  => 'nullable|numeric|gt:minPrice|min:0',
            'special'   => 'nullable|in:true,false',
            'onSale'    => 'nullable|in:true,false',
        ]);

        $perPage  = (int) ($validated['limit'] ?? 12);
        $search   = trim($validated['search'] ?? '');
        $category = $validated['category'] ?? null;
        $brand    = $validated['brand'] ?? null;
        $minPrice = $validated['minPrice'] ?? 0;
        $maxPrice = $validated['maxPrice'] ?? null;
        $special  = $validated['special'] ?? false;
        $onSale   = $validated['onSale'] ?? false;

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

            if ($categoryId) $query->where('category_id', $categoryId);
        }

        if ($brand) {
            $brandId = Brand::active()->where('website_id', $this->website->id)
                ->where('name', $brand)->value('id');;

            if ($brandId) $query->where('brand_id', $brandId);
        }

        if ($special) {
            $query->where('is_special', true);
        }

        if ($onSale) {
            $query->where('is_discount', true);
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
