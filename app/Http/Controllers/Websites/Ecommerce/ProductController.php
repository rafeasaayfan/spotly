<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\ProductRequest;
use App\Services\Websites\Ecommerce\AddToCartService;
use App\Services\Websites\Ecommerce\ProductService;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{
    /**
     * Display the product detail page.
     */
    public function index(string $slug)
    {
        $productService = new ProductService($this->website->id);

        $product = $productService->getProductBySlug($slug, $this->cartItems());
        $product->increment('views_count');

        return $this->inertiaRender('pages/Product', [
            'colors' => $this->websiteTemplate()->templateColor,
            'iniProduct' => $product,
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
            $validated = $request->validated();

            $addToCartService = new AddToCartService($this->website->id);
            $result = $addToCartService->addToCart($validated, $this->cartItems());
            
            if ($result['success'] === false) {
                return $this->jsonError($result['message']);
            } 

            return $this->jsonSuccess($result['message'], $result['data']);
        } catch (\Exception $e) {
            Log::error('ProductController@addToCart Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->logResponse('ProductController@addToCart', $e, $e->getTraceAsString());
        }
    }
}
