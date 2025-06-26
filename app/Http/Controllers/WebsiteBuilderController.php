<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteBuilderRequest;
use App\Models\Country;
use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebsiteBuilderController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');

        $websiteTypes = WebsiteType::active()->get();
        $typeId = WebsiteType::where('type', $type)->value('id');

        $countries = Country::active()->get();
        $countries->transform(function ($item) {
            $item->flag = $item->getFirstMediaUrl('flag');
            return $item;
        });

        return Inertia::render('WebsiteBuilder/Wizard', [
            'websiteTypes' => $websiteTypes,
            'type' => $type,
            'typeId' => $typeId,
            'countries' => $countries,
        ]);
    }

    public function store(WebsiteBuilderRequest $request) {}
}
