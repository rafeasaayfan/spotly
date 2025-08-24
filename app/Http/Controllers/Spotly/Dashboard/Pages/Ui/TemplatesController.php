<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Template;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Ui\Templates\StoreTemplateRequest;
use App\Http\Requests\Dashboard\Pages\Ui\Templates\UpdateTemplateRequest;
use Illuminate\Support\Facades\Auth;

class TemplatesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Template::query();

        $columnsSearching = ['createdBy.name', 'websiteType.type'];
        $columnsSelection = [];
        $relations = ['createdBy_name', 'websiteType_type'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/ui/templates/Templates', ['templates' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websiteTypes = $this->getRelation('websiteType', ['type']);

            return $this->jsonSuccess('', [
                'websiteTypes' => $websiteTypes,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplatesController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateRequest $request)
    {
        try {
            $validated = $request->validated();

            $template = new Template($validated);
            $template->created_by = Auth::id();

            $template->save();

            return $this->redirectSuccess('dashboard.templates.index', 'Template created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplatesController@store', $e, 'An error occurred while creating the template');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = Template::with(['createdBy', 'websiteType'])->findOrFail($id);
            $template = $this->flattenRelationData($query, ['createdBy_name', 'websiteType_type']);

            return $this->jsonSuccess('', [
                'data' => $template,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplatesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $template = Template::findOrFail($id);
            $websiteTypes = $this->getRelation('websiteType', ['type']);

            return $this->jsonSuccess('', [
                'data' => $template,
                'websiteTypes' => $websiteTypes,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplatesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateRequest $request, Template $template)
    {
        try {
            $validated = $request->validated();

            $template->fill($validated);
            $template->save();

            return $this->redirectSuccess('dashboard.templates.index', 'Template updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplatesController@update', $e, 'An error occurred while updating the template');
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
                'ids.*' => 'integer|exists:templates,id',
            ]);

            Template::destroy($validated['ids']);

            return $this->backSuccess('Template(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplatesController@destroy', $e, 'An error occurred while deleting the template(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $template = Template::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $template->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Template activated successfully.'
                : 'Template deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('TemplatesController@toggleActive', $e, 'An error occurred while updating the template status');
        }
    }
}
