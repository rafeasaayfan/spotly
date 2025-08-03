<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteBuilder\CustomColorsRequest;
use App\Http\Requests\WebsiteBuilder\WebsiteBuilderRequest;
use App\Models\Country;
use App\Models\Template;
use App\Models\TemplateTemplateColor;
use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebsiteBuilderController extends Controller
{
    /**
     * Show the website builder wizard page.
    */
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

        $cities = config('cities.lebanon');

        $templates = Template::active()->where('website_type_id', $typeId)
            ->with(['templateColors'])
            ->get();

        return Inertia::render('websiteBuilder/Wizard', [
            'websiteTypes' => $websiteTypes,
            'type' => $type,
            'typeId' => $typeId,
            'countries' => $countries,
            'cities' => $cities,
            'templates' => $templates,
        ]);
    }

    /**
     * Get the template colors for a specific template.
    */
    public function getTemplateTemplateColors(Request $request)
    {
        $templateId = $request->input('templateId');

        $templateTemplateColors = TemplateTemplateColor::with(['template:id,name', 'templateColor'])
            ->where('template_id', $templateId)
            ->get();
        $templateTemplateColors->transform(function ($item) {
            $item->images = $item->getMedia('images')->toArray();
            return $item;
        });

        return response()->json([
            'templateTemplateColors' => $templateTemplateColors
        ]);
    }

    /**
     * Handle custom color selection for a template.
    */
    public function customColors(CustomColorsRequest $request)
    {
        return response()->json([
            'validate' => true,
            'message' => 'Your custom colors selected successfully.'
        ]);
    }

    /**
     * Store the website builder data.
    */
    public function store(WebsiteBuilderRequest $request, string $step)
    {
        // $validated = $request->validated();

        switch ($step) {
            case 1:
                return redirect()->back()->with('message', 'First step completed!');
            case 2:
                return redirect()->back()->with('message', 'Second step completed!');
            case 3:
                return redirect()->back()->with('message', 'Third step completed!');
            case 4:
                return redirect()->back()->with('message', 'Website created successfully!');
            default:
                return redirect()->back()->with('error', 'Invalid step!');
        }
    }
}
