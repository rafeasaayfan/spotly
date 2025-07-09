<?php

namespace App\Http\Controllers\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Models\TemplateColor;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateColors\StoreTemplateColorRequest;
use App\Http\Requests\Dashboard\Pages\Ui\TemplateColors\UpdateTemplateColorRequest;
use Illuminate\Support\Facades\Auth;

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

        // $data->transform(function ($item) {
        //    $item->image = $item->getFirstMediaUrl('image');
        //    return $item;
        // });

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
        $validated = $request->validated();
        // unset($validated['image']);

        $templateColor = new TemplateColor($validated);

        $templateColor->created_by = Auth::id();

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //    $templatecolor->addMediaFromRequest('image')
        //        ->toMediaCollection('image');
        // }

        $templateColor->save();

        return redirect()->route('dashboard.templateColors.index')->with('message', 'TemplateColor created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = TemplateColor::with('createdBy')->findOrFail($id);
        $templateColor = $this->flattenRelationData($query, ['createdBy_name']);

        return response()->json([
            'data' => $templateColor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $templatecolor = TemplateColor::findOrFail($id);
        
        // $templatecolor->image = $templatecolor->getFirstMediaUrl('image');

        return response()->json([
            'data' => $templatecolor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateColorRequest $request, TemplateColor $templateColor)
    {
        $validated = $request->validated();
        // unset($validated['image']);

        $templateColor->fill($validated);

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //    $templatecolor->clearMediaCollection('image');
        //    $templatecolor->addMediaFromRequest('image')
        //        ->toMediaCollection('image');
        // }

        $templateColor->save();

        return redirect()->route('dashboard.templateColors.index')->with('message', 'TemplateColor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:template_colors,id',
        ]);

        TemplateColor::destroy($validated['ids']);

        return redirect()->back()->with('message', __('TemplateColor(s) deleted successfully.'));
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
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
    }
}
