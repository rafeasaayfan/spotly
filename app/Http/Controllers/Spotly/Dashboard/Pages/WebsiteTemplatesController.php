<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\WebsiteTemplate;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\WebsiteTemplates\StoreWebsiteTemplateRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteTemplates\UpdateWebsiteTemplateRequest;

class WebsiteTemplatesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = WebsiteTemplate::query();

        $columnsSearching = ['website.name', 'template.name', 'templateColor.name'];
        $columnsSelection = [];
        $relations = ['website_name', 'template_name', 'templateColor_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/websiteTemplates/WebsiteTemplates', ['websiteTemplates' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);
            $templates = $this->getRelation('template', ['name']);
            $templateColors = $this->getRelation('templateColor', ['name']);

            return $this->jsonSuccess('', [
                'websites' => $websites,
                'templates' => $templates,
                'templateColors' => $templateColors,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteTemplatesController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteTemplateRequest $request)
    {
        try {
            $validated = $request->validated();

            $websiteTemplate = new WebsiteTemplate($validated);

            $websiteTemplate->save();

            return $this->redirectSuccess('dashboard.websiteTemplates.index', 'Website Template created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTemplatesController@store', $e, 'An error occurred while creating the Website Template');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $websiteTemplate = WebsiteTemplate::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $websiteTemplate,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteTemplatesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $websiteTemplate = WebsiteTemplate::findOrFail($id);
            $websites = $this->getRelation('website', ['name']);
            $templates = $this->getRelation('template', ['name']);
            $templateColors = $this->getRelation('templateColor', ['name']);

            return $this->jsonSuccess('', [
                'data' => $websiteTemplate,
                'websites' => $websites,
                'templates' => $templates,
                'templateColors' => $templateColors,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteTemplatesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteTemplateRequest $request, WebsiteTemplate $websiteTemplate)
    {
        try {
            $validated = $request->validated();

            $websiteTemplate->fill($validated);

            $websiteTemplate->save();

            return $this->redirectSuccess('dashboard.websiteTemplates.index', 'Website Template updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTemplatesController@update', $e, 'An error occurred while updating the Website Template');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:website_templates,id',
            ]);

            WebsiteTemplate::destroy($validated['ids']);

            return $this->backSuccess('Website Template(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTemplatesController@destroy', $e, 'An error occurred while deleting the Website Template(s)');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $websiteTemplate = WebsiteTemplate::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $websiteTemplate->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Website Template activated successfully.'
                : 'Website Template deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTemplatesController@toggleActive', $e, 'An error occurred while updating the Website Template status');
        }
    }
}
