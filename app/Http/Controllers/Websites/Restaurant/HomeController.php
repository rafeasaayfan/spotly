<?php

namespace App\Http\Controllers\Websites\Restaurant;

use App\Http\Controllers\Websites\BaseController;
use App\Models\Category;
use App\Models\WebsiteMessage;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    /**
     * Display the home detail page.
     */
    public function index()
    {
        $categories = Category::where('website_id', $this->website->id)->active()->inHome()
            ->withCount('restaurantMenuItems')->get();

        return $this->inertiaRender(
            'pages/home/Home',
            [
                'colors' => $this->websiteTemplate()->templateColor,
                'websiteNameAndLogo' => $this->websiteNameAndLogo(),
                'websiteFooterData' => $this->websiteFooterData(),
                // 'homeSpecialProducts' => $homeSpecialProducts->isNotEmpty() ? $homeSpecialProducts : $homeProducts,
                // 'homeProducts' => $homeProducts,
                'categories' => $categories,
                'aboutUs' => $this->website->about_us,
                'aboutUsAr' => $this->website->about_us_ar,
                'cartItemsCount' => $this->cartItems()?->count()
            ],
            true
        );
    }

    public function contactUs(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|lowercase|email:rfc,dns',
            'subject' => 'required|string|max:40',
            'type' => 'required|string|max:30|in:support,suggestion,complaint,other',
            'message' => 'required|string|max:255',
        ]);

        $data = array_merge([
            'website_id' => $this->website->id
        ], $validated);

       WebsiteMessage::create($data);

        return $this->backSuccess(__('messages.thank_message'));
    }
}
