<?php

namespace App\Http\Controllers\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Models\TemplateTemplateColor;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors\StoreTemplateTemplateColorRequest;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors\UpdateTemplateTemplateColorRequest;
use Illuminate\Support\Facades\Log;

class TemplateTemplateColorsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TemplateTemplateColor::query();

        $columnsSearching = ['template.name', 'templateColor.name'];
        $columnsSelection = [];
        $relations = ['template_name', 'templateColor_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        // $data->transform(function ($item) {
        //    $item->image = $item->getFirstMediaUrl('image');
        //    return $item;
        // });

        return Inertia::render('dashboard/pages/ui/templateTemplateColors/TemplateTemplateColors', [
            'templateTemplateColors' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $templates = $this->getRelation('template', ['name']);
            $templateColors = $this->getRelation('templateColor', ['name']);

            return response()->json([
                'templates' => $templates,
                'templateColors' => $templateColors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplateTemplateColorsController@create',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateTemplateColorRequest $request)
    {
        try {
            $validated = $request->validated();

            $templateTemplateColor = new TemplateTemplateColor($validated);
            $templateTemplateColor->save();

            // Handle multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $templateTemplateColor->addMedia($image)
                            ->toMediaCollection('images');
                    }
                }
            }

            return redirect()->route('dashboard.templateTemplateColors.index')->with('message', 'TemplateTemplateColor created successfully');
        } catch (\Exception $e) {
            Log::error('Error on TemplateTemplateColorsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the template template color. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = TemplateTemplateColor::with(['template', 'templateColor'])->findOrFail($id);
            $query->images = $query->getMedia('images');

            $templateTemplateColor = $this->flattenRelationData($query, ['template_name', 'templateColor_name']);

            return response()->json([
                'data' => $templateTemplateColor,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplateTemplateColorsController@show',
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
            $templateTemplateColor = TemplateTemplateColor::findOrFail($id);
            $templates = $this->getRelation('template', ['name']);
            $templateColors = $this->getRelation('templateColor', ['name']);
            // $templatetemplatecolor->image = $templatetemplatecolor->getFirstMediaUrl('image');

            return response()->json([
                'data' => $templateTemplateColor,
                'templates' => $templates,
                'templateColors' => $templateColors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplateTemplateColorsController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateTemplateColorRequest $request, TemplateTemplateColor $templateTemplateColor)
    {
        try {
            $validated = $request->validated();

            $templateTemplateColor->fill($validated);
            $templateTemplateColor->save();

            if ($request->hasFile('images')) {
                // Clear existing images if new ones are uploaded
                $templateTemplateColor->clearMediaCollection('images');

                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $templateTemplateColor->addMedia($image)
                            ->toMediaCollection('images');
                    }
                }
            }

            return redirect()->route('dashboard.templateTemplateColors.index')->with('message', 'TemplateTemplateColor updated successfully');
        } catch (\Exception $e) {
            Log::error('Error on TemplateTemplateColorsController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the template template color. Please try again.');
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

            return redirect()->back()->with('message', __('TemplateTemplateColor(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error on TemplateTemplateColorsController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the template template color(s). Please try again.');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleDefault(Request $request, $id)
    {
        try {
            $templateTemplateColor = TemplateTemplateColor::findOrFail($id);

            $validated = $request->validate([
                'is_default' => 'required|boolean',
            ]);

            // change the old default because just one can be a default
            if ($validated['is_default']) {
                TemplateTemplateColor::where('template_id', $templateTemplateColor->template_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $templateTemplateColor->update([
                'is_default' => $validated['is_default'],
            ]);

            $message = $validated['is_default']
                ? 'Template color set as default successfully.'
                : 'Template color removed from default successfully.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error on TemplateTemplateColorsController@toggleDefault: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the default status. Please try again.');
        }
    }
}
