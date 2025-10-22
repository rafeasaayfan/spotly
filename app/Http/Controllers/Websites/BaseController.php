<?php

namespace App\Http\Controllers\Websites;

use App\Http\Controllers\Controller;
use App\Models\EcommerceCartItem;
use App\Models\Website;
use App\Models\WebsiteTemplate;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected Website $website;

    /**
     * BaseController constructor.
    */
    public function __construct()
    {
        $this->website = app('website')->load(['media', 'activeWebsiteTemplate']);
    }

    /**
     * Retrieve the current website template with its colors.
     *
     * @return WebsiteTemplate
     */
    protected function websiteTemplate(): WebsiteTemplate
    {
        $websiteTemplateId = $this->website->activeWebsiteTemplate?->id;
        return WebsiteTemplate::where('website_id', $this->website->id)
            ->where('id', $websiteTemplateId)
            ->with('templateColor')
            ->firstOrFail();
    }

    /**
     * Get the website's name and logo URLs.
     *
     * @return array
     */
    protected function websiteNameAndLogo(): array
    {
        return [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];
    }

    /**
     * Get the website's footer data including social media links and contact info.
     *
     * @return array
     */
    protected function websiteFooterData(): array
    {
        return [
            'instagram' => $this->website->instagram,
            'tiktok' => $this->website->tiktok,
            'facebook' => $this->website->facebook,
            'youtube' => $this->website->youtube,
            'email' => $this->website->email,
            'phone_number' => $this->website->phone_number,
            'address' => $this->website->address,
        ];
    }

    /**
     * Get the current user's cart items for the e-commerce website.
     * Differentiates between authenticated users and guests (session-based).
     *
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    protected function cartItems()
    {
        if ($this->website->websiteType->type === 'e-commerce') {
            $cartItems = EcommerceCartItem::query();
            if (Auth::guard('website')->check()) {
                return $cartItems->whereHas('cart', function ($q) {
                    $q->where('website_id', $this->website->id)
                        ->where('website_user_id', Auth::guard('website')->id());
                });
            } else {
                return $cartItems->whereHas('cart', function ($q) {
                    $q->where('website_id', $this->website->id)
                        ->where('session_id', session()->getId());
                });
            }
        }
        // Return null if not an ecommerce website
        return null;
    }
}
