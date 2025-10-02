<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public $website;

    public function __construct()
    {
        $this->website = app('website')->load('media');
    }

    public function index()
    {
        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return Inertia::render('websites/e-commerce/dashboard/Dashboard', [
            'websiteNameAndLogo' => $websiteNameAndLogo
        ]);
    }
}
