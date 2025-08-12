<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteBuilder\CustomColorsRequest;
use App\Http\Requests\WebsiteBuilder\WebsiteBuilderRequest;
use App\Models\Country;
use App\Models\Template;
use App\Models\TemplateColor;
use App\Models\TemplateTemplateColor;
use App\Models\Website;
use App\Models\WebsiteTemplate;
use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        $typeId = $websiteTypes->firstWhere('type', $type)?->id;

        $countries = Country::with('media')->active()->get();
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
            'validate' => $request->validated() ? true : false,
            'message' => 'Your custom colors selected successfully.'
        ]);
    }

    /**
     * Handel the wizard data.
     */
    public function wizard(WebsiteBuilderRequest $request, string $step)
    {
        switch ($step) {
            case 1:
            case 2:
            case 3:
                return redirect()->back()->with('message', "Step {$step} completed!");
            case 4:
                if (!Auth::check()) {
                    session(['pending_website_creation' => true]);

                    return redirect()->route('register')
                        ->with('message', 'Please register or login to continue.');
                }
                return $this->store();
                break;
            default:
                return redirect()->back()->with('error', 'Invalid step!');
        }
    }

    /**
     * Store the website builder data.
     */
    public function store()
    {
        try {
            $websiteTableData = array_merge(
                session('wizard_step_1', []),
                session('wizard_step_2', []),
                ['owner_id' => Auth::id()]
            );
            $thirdStepData = session('wizard_step_3', []);

            $website = Website::create($websiteTableData);

            $this->storeLogos($website, $thirdStepData);
            $this->storeTemplate($website->id, $thirdStepData);

            session()->forget(['wizard_step_1', 'wizard_step_2', 'wizard_step_3', 'pending_website_creation']);
            session()->regenerate();

            return redirect()->route('dashboard.index')->with('message', 'Your website has been created!');

        } catch (\Exception $e) {
            Log::error('Error in WebsiteBuilderController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with(['message' => "An error occurred while creating the website. Please try again."], 500);
        }
    }

    /**
     * Store the website template data.
     */
    public function storeTemplate($websiteId, $websiteTemplateData)
    {
        $templarecolorId = '';

        try {

            if (!empty($websiteTemplateData['custom_template_color']) && $websiteTemplateData['custom_template_color']) {
                $templarecolors = TemplateColor::create($websiteTemplateData['colors']);
                $templarecolorId = $templarecolors->id;
            } else {
                $templarecolorId = $websiteTemplateData['template_color_id'];
            }

            WebsiteTemplate::create([
                'website_id' => $websiteId,
                'template_id' => $websiteTemplateData['template_id'],
                'template_color_id' => $templarecolorId,
                'is_active' => 1
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in WebsiteBuilderController@storeTemplate: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Store the website logos.
     */
    public function storeLogos($website, $thirdStepData)
    {
        try {
            if (
                !empty($thirdStepData['light_logo']) && !empty($thirdStepData['dark_logo']) &&
                Storage::exists($thirdStepData['light_logo']) && Storage::exists($thirdStepData['dark_logo'])
            ) {
                $website->addMedia(Storage::path($thirdStepData['light_logo']))
                    ->toMediaCollection('light_logo');

                $website->addMedia(Storage::path($thirdStepData['dark_logo']))
                    ->toMediaCollection('dark_logo');

                Storage::delete($thirdStepData['light_logo']);
                Storage::delete($thirdStepData['dark_logo']);
            }

        } catch (\Exception $e) {
            Log::error('Error in WebsiteBuilderController@storeLogos: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
