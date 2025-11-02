<?php

namespace App\Http\Controllers\Spotly\Client\Websites\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreateWebsiteUiRequest;
use App\Models\TemplateTemplateColor;
use App\Models\Website;
use App\Models\WebsiteTemplate;
use App\Services\UiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UiWebsiteController extends Controller
{
    /**
     * Display the ui templates for a website.
     */
    public function index(Website $website)
    {
        Gate::authorize('ui', $website);

        try {
            $websiteTemplate = WebsiteTemplate::where('website_id', $website->id)
                ->with(['template:id,name', 'templateColor:id,name'])->get();

            $website->load(['websiteType:id,type']);

            $templateTemplateColors = TemplateTemplateColor::where('website_type_id', $website->website_type_id)
                ->with(['media', 'template', 'templateColor'])
                ->get();

            return $this->inertiaRender('client/myWebsites/actions/Ui', [
                'websiteTemplates' => $websiteTemplate,
                'website' => $website,
                'templateTemplateColors' => $templateTemplateColors,
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('UiWebsiteController@index', $e, 'An error occurred while loading the website UI templates');
        }
    }

    /**
     * Change the template Activation for a website.
     */
    public function toggleActive(Request $request, Website $website)
    {
        Gate::authorize('ui', $website);

        $validate = $request->validate([
            'is_active' => 'required|boolean',
            'websiteTemplateId' => 'required|exists:website_templates,id'
        ]);

        try {
            $requestWebsiteTemplate = WebsiteTemplate::where('id', $validate['websiteTemplateId'])
                ->where('website_id', $website->id)
                ->firstOrFail();

            $activeWebsiteTemplate = WebsiteTemplate::where('website_id', $website->id)
                ->where('is_active', true)
                ->firstOrFail();

            if (!$validate['is_active'] && $requestWebsiteTemplate->id === $activeWebsiteTemplate->id) {
                return $this->backError(__('messages.cant_deactivate_active'));
            }

            if ($validate['is_active'] && $requestWebsiteTemplate->id !== $activeWebsiteTemplate->id) {
                $activeWebsiteTemplate->is_active = false;
                $activeWebsiteTemplate->save();
                $requestWebsiteTemplate->is_active = true;
                $requestWebsiteTemplate->save();
            }

            return $this->backSuccess(__('messages.website_template_activated'));
        } catch (\Exception $e) {
            return $this->logResponse('UiWebsiteController@toggleActive', $e, 'An error occurred while change the website template status');
        }
    }

    /**
     * Create the specified resource to storage.
     */
    public function create(CreateWebsiteUiRequest $request, Website $website)
    {
        Gate::authorize('ui', $website);

        try {
            $uiService = new UiService(
                $website->id,
                $request->template_color_id,
                $request->template_id,
                $request->template_images,
                $request->is_custom,
                $request->colors
            );
            $result = $uiService->storeTemplate();

            if($result === true) {
                return $this->redirectSuccess('client.myWebsite.ui', __('messages.website_template_created'), 'success', [
                    'website' => $website->id
                ]);
            } 

            return $this->backError($result ?? '');
        } catch (\Exception $e) {
            return $this->logResponse('UiWebsiteController@create', $e, 'An error occurred while creating the website template');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Website $website)
    {
        Gate::authorize('ui', $website);

        $validate = $request->validate([
            'websiteTemplateId' => 'required|exists:website_templates,id'
        ]);

        try {
            $websiteTemplates = WebsiteTemplate::where('website_id', $website->id);
            if ($websiteTemplates->count() === 1) {
                return $this->backError(__('messages.cant_delete_last'));
            }

            $websiteTemplate = $websiteTemplates->findOrFail($validate['websiteTemplateId']);
            if ($websiteTemplate->is_active) {
                return $this->backError(__('messages.cant_delete_active'));
            }

            $websiteTemplate->delete();

            return $this->redirectSuccess('client.myWebsite.ui', __('messages.website_template_deleted'), 'success', [
                'website' => $website->id
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('UiWebsiteController@destroy', $e, 'An error occurred while deleting the website template');
        }
    }
}
