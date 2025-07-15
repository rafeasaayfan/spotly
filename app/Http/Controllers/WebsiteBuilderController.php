<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteBuilderRequest;
use App\Models\Country;
use App\Models\Template;
use App\Models\TemplateTemplateColor;
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
        $cities = config('lebanon.cities');


        $templates = Template::active()->where('website_type_id', $typeId)
            ->with(['templateColors'])
            ->get();

        $templateTemplateColors = TemplateTemplateColor::with(['templateColor:id,name'])->where('template_id', 1)->get();
        $templateTemplateColors->transform(function ($item) {
            $item->images = $item->getMedia('images');
            return $item;
        });

        return Inertia::render('websiteBuilder/Wizard', [
            'websiteTypes' => $websiteTypes,
            'type' => $type,
            'typeId' => $typeId,
            'countries' => $countries,
            'cities' => $cities,
            'templates' => $templates,
            'templateTemplateColors' => $templateTemplateColors,
        ]);
    }

    public function store(WebsiteBuilderRequest $request) {}


    public function getTemplateColors(Request $request)
    {
        $templateId = $request->input('template_id');
        $templateTemplateColors = TemplateTemplateColor::where('template_id', $templateId)->get();

        $templateTemplateColors->transform(function ($item) {
            $item->images = $item->getMedia('images');
            return $item;
        });

        return response()->json($templateTemplateColors);
    }
}
