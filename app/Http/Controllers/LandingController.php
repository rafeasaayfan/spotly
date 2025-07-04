<?php

namespace App\Http\Controllers;

use App\Models\WebsiteType;
use Inertia\Inertia;
use App\Models\EmailSubscriber;
use App\Models\Message;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $websiteTypes = WebsiteType::all();

        return Inertia::render('landing/Landing')->with([
            'websiteTypes' => $websiteTypes,
        ]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:email_subscribers,email',
        ]);

        EmailSubscriber::create($validated);

        return redirect()->back()->with('message', 'Subscription successful!');
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

        $message = Message::create($validated);
        $message->status = 'new';
        $message->save();

        return redirect()->back()->with('message', 'Thank you for your message!');
    }
}
