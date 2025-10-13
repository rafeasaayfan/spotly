<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard;

use App\Http\Controllers\Websites\BaseController;
// use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends BaseController
{
    public function index()
    {
        return Inertia::render('websites/e-commerce/dashboard/Dashboard', [
            'websiteNameAndLogo' => $this->websiteNameAndLogo
        ]);
    }
}
