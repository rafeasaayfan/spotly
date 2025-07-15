<?php

namespace App\Http\Controllers\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Models\TemplateTemplateColor;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors\StoreTemplateTemplateColorRequest;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors\UpdateTemplateTemplateColorRequest;

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
        $templates = $this->getRelation('template', ['name']);
        $templateColors = $this->getRelation('templateColor', ['name']);

        return response()->json([
            'templates' => $templates,
            'templateColors' => $templateColors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateTemplateColorRequest $request)
    {
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = TemplateTemplateColor::with(['template', 'templateColor'])->findOrFail($id);
        $query->images = $query->getMedia('images');

        $templateTemplateColor = $this->flattenRelationData($query, ['template_name', 'templateColor_name']);

        return response()->json([
            'data' => $templateTemplateColor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $templateTemplateColor = TemplateTemplateColor::findOrFail($id);
        $templates = $this->getRelation('template', ['name']);
        $templateColors = $this->getRelation('templateColor', ['name']);

        // $templatetemplatecolor->image = $templatetemplatecolor->getFirstMediaUrl('image');

        return response()->json([
            'data' => $templateTemplateColor,
            'templates' => $templates,
            'templateColors' => $templateColors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateTemplateColorRequest $request, TemplateTemplateColor $templateTemplateColor)
    {
        $validated = $request->validated();

        $templateTemplateColor->fill($validated);
        $templateTemplateColor->save();

        // Handle multiple images
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:template_template_colors,id',
        ]);

        TemplateTemplateColor::destroy($validated['ids']);

        return redirect()->back()->with('message', __('TemplateTemplateColor(s) deleted successfully.'));
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleDefault(Request $request, $id)
    {
        $templateTemplateColor = TemplateTemplateColor::findOrFail($id);

        $validated = $request->validate([
            'is_default' => 'required|boolean',
        ]);

        if ($validated['is_default']) {
            // Unset previous default for this template_id
            TemplateTemplateColor::where('template_id', $templateTemplateColor->template_id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        // Set the selected one as default (or not)
        $templateTemplateColor->update([
            'is_default' => $validated['is_default'],
        ]);

        $message = $validated['is_default']
            ? 'Template color set as default successfully.'
            : 'Template color removed from default successfully.';

        return redirect()->back()->with('message', $message);
    }
}
