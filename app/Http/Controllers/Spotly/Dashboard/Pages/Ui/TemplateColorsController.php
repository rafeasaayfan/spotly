<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Models\TemplateColor;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateColors\StoreTemplateColorRequest;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateColors\UpdateTemplateColorRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TemplateColorsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TemplateColor::query();

        $columnsSearching = ['createdBy.name', 'name'];
        $columnsSelection = [];
        $relations = ['createdBy_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/ui/templateColors/TemplateColors', [
            'templateColors' => $data,
        ]);
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

            return redirect()->route('dashboard.templateColors.index')->with('message', 'Template Color created successfully');
        } catch (\Exception $e) {
            Log::error('Error in TemplateColorsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating he Template Color. Please try again.');
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

            return response()->json([
                'data' => $templateColor,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplateColorsController@show',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $templateColor = TemplateColor::findOrFail($id);

            return response()->json([
                'data' => $templateColor,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplateColorsController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
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

            return redirect()->route('dashboard.templateColors.index')->with('message', 'Template Color updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in TemplateColorsController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating he Template Color. Please try again.');
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

            return redirect()->back()->with('message', __('Template Color(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in TemplateColorsController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the template(s). Please try again.');
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

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in TemplateColorsController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the template color status. Please try again.');
        }
    }
}
