<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\TemplateTemplateColor;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors\StoreTemplateTemplateColorRequest;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors\UpdateTemplateTemplateColorRequest;

class TemplateTemplateColorsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = TemplateTemplateColor::query();

        $columnsSearching = ['template.name', 'templateColor.name', 'websiteType.type'];
        $columnsSelection = [];
        $relations = ['template_name', 'templateColor_name', 'media', 'websiteType_type'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender(
            'dashboard/pages/ui/templateTemplateColors/TemplateTemplateColors',
            [
                'templateTemplateColors' => $data
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $templates = $this->getRelation('template', ['name']);
            $templateColors = $this->getRelation('templateColor', ['name']);
            $websiteTypes = $this->getRelation('websiteType', ['type']);

            return $this->jsonSuccess('', [
                'templates' => $templates,
                'templateColors' => $templateColors,
                'websiteTypes' => $websiteTypes,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplateTemplateColorsController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateTemplateColorRequest $request)
    {

        try {
            $validated = $request->validated();
            $imagesData = $validated['uiImages'];
            unset($validated['uiImages']);

            $templateTemplateColor = new TemplateTemplateColor($validated);
            $templateTemplateColor->save();

            // Handle multiple images
            $templateTemplateColor->storeMediaImages($imagesData, 'uiImages');

            return $this->redirectSuccess('dashboard.templateTemplateColors.index', 'TemplateTemplateColor created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateTemplateColorsController@store', $e, 'An error occurred while creating the template template color');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = TemplateTemplateColor::with(['media', 'websiteType', 'template', 'templateColor'])->findOrFail($id);

            $templateTemplateColor = $this->flattenRelationData($query, ['websiteType_type', 'template_name', 'templateColor_name']);

            return $this->jsonSuccess('', [
                'data' => $templateTemplateColor,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplateTemplateColorsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $templateTemplateColor = TemplateTemplateColor::findOrFail($id);

            $templates = $this->getRelation('template', ['name']);
            $templateColors = $this->getRelation('templateColor', ['name']);
            $websiteTypes = $this->getRelation('websiteType', ['type']);

            return $this->jsonSuccess('', [
                'data' => $templateTemplateColor,
                'templates' => $templates,
                'templateColors' => $templateColors,
                'websiteTypes' => $websiteTypes,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TemplateTemplateColorsController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateTemplateColorRequest $request, TemplateTemplateColor $templateTemplateColor)
    {
        try {
            $validated = $request->validated();
            $imagesData = $validated['uiImages'];
            unset($validated['uiImages']);

            $templateTemplateColor->fill($validated);

            $templateTemplateColor->updateMediaImages($imagesData, 'uiImages');

            $templateTemplateColor->save();

            return $this->redirectSuccess('dashboard.templateTemplateColors.index', 'TemplateTemplateColor updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateTemplateColorsController@update', $e, 'An error occurred while updating the template template color');
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
                'ids.*' => 'integer|exists:template_template_colors,id',
            ]);

            TemplateTemplateColor::destroy($validated['ids']);

            return $this->backSuccess('TemplateTemplateColor(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('TemplateTemplateColorsController@destroy', $e, 'An error occurred while deleting the template template color(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $templateTemplateColor = TemplateTemplateColor::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $templateTemplateColor->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Template color activated successfully.'
                : 'Template color deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('TemplateTemplateColorsController@toggleActive', $e, 'An error occurred while updating the active status');
        }
    }


    // public function toggleDefault(Request $request, $id)
    // {
    //     try {
    //         $templateTemplateColor = TemplateTemplateColor::findOrFail($id);

    //         $validated = $request->validate([
    //             'is_default' => 'required|boolean',
    //         ]);

    //         // change the old default because just one can be a default
    //         if ($validated['is_default']) {
    //             TemplateTemplateColor::where('template_id', $templateTemplateColor->template_id)
    //                 ->where('is_default', true)
    //                 ->update(['is_default' => false]);
    //         }

    //         $templateTemplateColor->update([
    //             'is_default' => $validated['is_default'],
    //         ]);

    //         $message = $validated['is_default']
    //             ? 'Template color set as default successfully.'
    //             : 'Template color removed from default successfully.';

    //         return $this->backSuccess($message);
    //     } catch (\Exception $e) {
    //         return $this->logResponse('TemplateTemplateColorsController@toggleDefault', $e, 'An error occurred while updating the default status');
    //     }
    // }
}
 