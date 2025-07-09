<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebsiteTemplate;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\WebsiteTemplates\StoreWebsiteTemplateRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteTemplates\UpdateWebsiteTemplateRequest;

class WebsiteTemplatesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebsiteTemplate::query();

        $columnsSearching = ['website.name', 'template.name', 'templateColor.name'];
        $columnsSelection = [];
        $relations = ['website_name', 'template_name', 'templateColor_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);


        return Inertia::render('dashboard/pages/websiteTemplates/WebsiteTemplates', [
            'websiteTemplates' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $websites = $this->getRelation('website', ['name']);
        $templates = $this->getRelation('template', ['name']);
        $templateColors = $this->getRelation('templateColor', ['name']);

        return response()->json([
            'websites' => $websites,
            'templates' => $templates,
            'templateColors' => $templateColors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteTemplateRequest $request)
    {
        $validated = $request->validated();

        $websiteTemplate = new WebsiteTemplate($validated);

        $websiteTemplate->save();

        return redirect()->route('dashboard.websiteTemplates.index')->with('message', 'Website Template created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $websiteTemplate = WebsiteTemplate::findOrFail($id);

        return response()->json([
            'data' => $websiteTemplate,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $websiteTemplate = WebsiteTemplate::findOrFail($id);
        $websites = $this->getRelation('website', ['name']);
        $templates = $this->getRelation('template', ['name']);
        $templateColors = $this->getRelation('templateColor', ['name']);

        return response()->json([
            'data' => $websiteTemplate,
            'websites' => $websites,
            'templates' => $templates,
            'templateColors' => $templateColors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteTemplateRequest $request, WebsiteTemplate $websiteTemplate)
    {
        $validated = $request->validated();

        $websiteTemplate->fill($validated);

        $websiteTemplate->save();

        return redirect()->route('dashboard.websiteTemplates.index')->with('message', 'Website Template updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:website_templates,id',
        ]);

        WebsiteTemplate::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Website Template(s) deleted successfully.'));
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
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

        return redirect()->back()->with('message', $message);
    }
}
