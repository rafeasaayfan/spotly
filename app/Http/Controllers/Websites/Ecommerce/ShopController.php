<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\ShopRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Services\Websites\Ecommerce\ProductService;

class ShopController extends BaseController
{
    /**
     * Display the shop detail page.
     */
    public function index(ShopRequest $request)
    {
        $validated = $request->validated();

        $categories = Category::active()->where('website_id', $this->website->id)->get();
        $brands = Brand::active()->where('website_id', $this->website->id)->get();

        $productService = new ProductService($this->website->id);
        $products = $productService->getShopProducts($validated);

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
}
