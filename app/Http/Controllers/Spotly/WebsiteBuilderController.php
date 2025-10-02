<?php

namespace App\Http\Controllers\Spotly;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteBuilder\CustomColorsRequest;
use App\Http\Requests\WebsiteBuilder\WebsiteBuilderRequest;
use App\Jobs\WebsiteCreationMailJob;
use App\Models\Country;
use App\Models\Template;
use App\Models\TemplateTemplateColor;
use App\Models\Website;
use App\Models\WebsiteType;
use App\Services\UiService;
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

            $cities = config('cities.lebanon');

            $templateTemplateColors = TemplateTemplateColor::where('website_type_id', $typeId)
                ->with(['media', 'template', 'templateColor'])
                ->get();

            return $this->inertiaRender('websiteBuilder/Wizard', [
                'websiteTypes' => $websiteTypes,
                'type' => $type,
                'typeId' => $typeId,
                'countries' => $countries,
                'cities' => $cities,
                'templateTemplateColors' => $templateTemplateColors,
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteBuilderController@index', $e, 'An error occurred while fetching website builder data');
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
        try {
            $uiService = new UiService(
                $websiteId,
                $websiteTemplateData['template_color_id'],
                $websiteTemplateData['template_id'],
                $websiteTemplateData['template_images'] ?? [],
                $websiteTemplateData['custom_template_color'],
                $websiteTemplateData['colors'] ?? [],
            );
            $result = $uiService->createNewWebsiteTemplate();

            if ($result !== true) {
                return $this->backError($result ?? '');
            }
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteBuilderController@storeTemplate', $e, 'An error occurred while storing the website template');
        }
    }
}
