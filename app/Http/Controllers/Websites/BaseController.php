<?php

namespace App\Http\Controllers\Websites;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteTemplate;

class BaseController extends Controller
{
    protected Website $website;
    protected $websiteTemplate;
    protected array $websiteNameAndLogo;
    protected array $websiteFooterData;

    public function __construct()
    {
        $this->website = app('website')->load(['media', 'activeWebsiteTemplate']);

        $websiteTemplateId = $this->website->activeWebsiteTemplate?->id;
        $this->websiteTemplate = WebsiteTemplate::where('website_id', $this->website->id)
            ->where('id', $websiteTemplateId)
            ->with('templateColor')
            ->firstOrFail();

        $this->websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        $this->websiteFooterData = [
            'instagram' => $this->website->instagram,
            'tiktok' => $this->website->tiktok,
            'facebook' => $this->website->facebook,
            'youtube' => $this->website->youtube,
            'email' => $this->website->email,
            'phone_number' => $this->website->phone_number,
            'address' => $this->website->address,
        ];
    }
}
