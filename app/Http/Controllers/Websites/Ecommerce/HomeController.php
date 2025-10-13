<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Models\Category;
use App\Models\EcommerceCartItem;
use App\Models\EcommerceProduct;
use App\Models\WebsiteMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends BaseController
{
    public function index()
    {
        $homeSpecialProducts = EcommerceProduct::active()->inHome()->special()->whereHas('inStockVariants')
            ->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name'])->get();

        $homeProducts = EcommerceProduct::active()->inHome()->where('is_special', false)->whereHas('inStockVariants')
            ->with(['inStockVariants', 'category:id,name,ar_name', 'brand:id,name'])->get();

        $categories = Category::active()->inHome()->where('website_id', $this->website->id)
            ->withCount('ecommerceProducts')->get();

        $cartItemsCount = EcommerceCartItem::query();
        if(Auth::check()) {
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
            'pages/home/Home',
            [
                'colors' => $this->websiteTemplate->templateColor,
                'websiteNameAndLogo' => $this->websiteNameAndLogo,
                'websiteFooterData' => $this->websiteFooterData,
                'homeSpecialProducts' => $homeSpecialProducts->isNotEmpty() ? $homeSpecialProducts : $homeProducts,
                'homeProducts' => $homeProducts,
                'categories' => $categories,
                'aboutUs' => $this->website->about_us,
                'cartItemsCount' => $cartItemsCount
            ],
            true
        );
    }

    public function contactUs(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email',
            'subject' => 'required|string|max:40',
            'type' => 'required|string|max:30|in:support,suggestion,complaint,other',
            'message' => 'required|string|max:255',
        ]);

        $data = array_merge([
            'website_id' => $this->website->id
        ], $validated);

       WebsiteMessage::create($data);

        return $this->backSuccess('Thank you for your message!');
    }
}
