<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\TemplateColor;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateColors\StoreTemplateColorRequest;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateColors\UpdateTemplateColorRequest;
use Illuminate\Support\Facades\Auth;

class TemplateColorsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = TemplateColor::query();

        $columnsSearching = ['createdBy.name', 'name'];
        $columnsSelection = [];
        $relations = ['createdBy_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/ui/templateColors/TemplateColors', ['templateColors' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateColorRequest $request)
    {
        try {
            $validated = $request->validated();

            $templateColor = new TemplateColor($validated);
            $templateColor->created_by = Auth::id();
            $templateColor->save();

            return $this->redirectSuccess('dashboard.templateColors.index', 'Template Color created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateColorsController@store', $e, 'An error occurred while creating the Template Color');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = TemplateColor::with('createdBy')->findOrFail($id);
            $templateColor = $this->flattenRelationData($query, ['createdBy_name']);

            return $this->jsonSuccess('', [
                'data' => $templateColor,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplateColorsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $templateColor = TemplateColor::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $templateColor,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplateColorsController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateColorRequest $request, TemplateColor $templateColor)
    {
        try {
            $validated = $request->validated();

            $templateColor->fill($validated);
            $templateColor->save();

            return $this->redirectSuccess('dashboard.templateColors.index', 'Template Color updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateColorsController@update', $e, 'An error occurred while updating the Template Color');
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
                'ids.*' => 'integer|exists:template_colors,id',
            ]);

            TemplateColor::destroy($validated['ids']);

            return $this->backSuccess('Template Color(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateColorsController@destroy', $e, 'An error occurred while deleting the Template Color(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $templateColor = TemplateColor::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $templateColor->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Template Color activated successfully.'
                : 'Template Color deactivated successfully.';

            return $this->backSuccess('Template Color(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateColorsController@toggleActive', $e, 'An error occurred while updating the Template Color status');
        }
    }
}
