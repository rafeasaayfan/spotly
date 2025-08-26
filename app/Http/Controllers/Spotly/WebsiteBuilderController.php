<?php

namespace App\Http\Controllers\Spotly;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteBuilder\CustomColorsRequest;
use App\Http\Requests\WebsiteBuilder\WebsiteBuilderRequest;
use App\Jobs\WebsiteCreationMailJob;
use App\Models\Country;
use App\Models\Template;
use App\Models\TemplateColor;
use App\Models\TemplateTemplateColor;
use App\Models\Website;
use App\Models\WebsiteTemplate;
use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WebsiteBuilderController extends Controller
{
    /**
     * Show the website builder wizard page.
     */
    public function index(Request $request)
    {
        try {
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

            return $this->inertiaRender('websiteBuilder/Wizard', [
                'websiteTypes' => $websiteTypes,
                'type' => $type,
                'typeId' => $typeId,
                'countries' => $countries,
                'cities' => $cities,
                'templates' => $templates,
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteBuilderController@index', $e, 'An error occurred while fetching website builder data');
        }
    }

    /**
     * Get the template colors for a specific template.
     */
    public function getTemplateTemplateColors(Request $request)
    {
        try {
            $templateId = $request->input('templateId');

            $templateTemplateColors = TemplateTemplateColor::with([
                'media' => function($query) {
                    $query->where('collection_name', 'uiImages');
                },
                'template:id,name', 
                'templateColor'
            ])
            ->where('template_id', $templateId)
            ->get()
            ->transform(function ($item) {
                $item->uiImages = $item->media->toArray();
                unset($item->media); 
                return $item;
            });
    
            return $this->jsonSuccess('', ['templateTemplateColors' => $templateTemplateColors]);

        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteBuilderController@getTemplateTemplateColors', $e, 'An error occurred while fetching template');
        }
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
                return $this->backSuccess('Step ' . $step . ' completed!');
            case 4:
                if (!Auth::check()) {
                    session(['pending_website_creation' => true]);

                    return $this->redirectSuccess('register', 'Please register or login to continue', 'info');
                }
                return $this->store();
                break;
            default:
                return $this->backError('Invalid step!');
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

            WebsiteCreationMailJob::dispatch($website->owner_id, $website->name);

            return $this->redirectSuccess('client.myWebsites', 'Your website has been created!');

        } catch (\Exception $e) {
            return $this->logResponse('WebsiteBuilderController@store', $e, 'An error occurred while creating the website');
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
            return $this->logResponse('WebsiteBuilderController@storeLogos', $e, 'An error occurred while storing the website logos');
        }
    }

    /**
     * Store the website template data.
     */
    public function storeTemplate($websiteId, $websiteTemplateData)
    {
        $templarecolorId = '';
        $template_images = [''];

        try {
            // check if is a custom colors
            if (!empty($websiteTemplateData['custom_template_color']) && $websiteTemplateData['custom_template_color']) {
                $templarecolors = TemplateColor::create($websiteTemplateData['colors']);
                $templarecolorId = $templarecolors->id;
            } else {
                $templarecolorId = $websiteTemplateData['template_color_id'];
                $template_images = $websiteTemplateData['template_images'];
            }

            WebsiteTemplate::create([
                'website_id' => $websiteId,
                'template_id' => $websiteTemplateData['template_id'],
                'template_color_id' => $templarecolorId,
                'template_images' => $template_images,
                'is_custom' => $websiteTemplateData['custom_template_color'],
                'is_active' => 1
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteBuilderController@storeTemplate', $e, 'An error occurred while storing the website template');
        }
    }
}
