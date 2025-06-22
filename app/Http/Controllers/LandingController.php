<?php

namespace App\Http\Controllers;

use App\Models\WebsiteType;
use Inertia\Inertia;
use App\Models\EmailSubscriber;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index() {
        $websiteTypes = WebsiteType::all();

        return Inertia::render('landing/Landing')->with([
            'websiteTypes' => $websiteTypes,
        ]);
    }

    public function subscribe(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:email_subscribers,email',
        ]);

        EmailSubscriber::create($validated);

        return redirect()->back()->with('message', 'Subscription successful!');
    }
}
