<?php

namespace App\Http\Controllers;

use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index() {
        $websiteTypes = WebsiteType::all();

        return Inertia::render('landing/Landing')->with([
            'websiteTypes' => $websiteTypes,
        ]);
    }
}
