<?php

namespace App\Http\Controllers\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Ui\Templates\StoreTemplateRequest;
use App\Http\Requests\Dashboard\Pages\Ui\Templates\UpdateTemplateRequest;
use Illuminate\Support\Facades\Auth;

class TemplatesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Template::query();

        $columnsSearching = ['createdBy.name', 'websiteType.type'];
        $columnsSelection = [];
        $relations = ['createdBy_name', 'websiteType_type'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        // $data->transform(function ($item) {
        //    $item->image = $item->getFirstMediaUrl('image');
        //    return $item;
        // });

        return Inertia::render('dashboard/pages/ui/templates/Templates', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $websiteTypes = $this->getRelation('websiteType', ['type']);

        return response()->json([
            'websiteTypes' => $websiteTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateRequest $request)
    {
        $validated = $request->validated();
        // unset($validated['image']);

        $template = new Template($validated);

        $template->created_by = Auth::id();

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //    $ui->addMediaFromRequest('image')
        //        ->toMediaCollection('image');
        // }

        $template->save();

        return redirect()->route('dashboard.templates.index')->with('message', 'template created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = Template::with(['createdBy', 'websiteType'])->findOrFail($id);
        $template = $this->flattenRelationData($query, ['createdBy_name', 'websiteType_type']);

        // $ui->image = $ui->getFirstMediaUrl('image');

        return response()->json([
            'data' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = Template::findOrFail($id);
        $websiteTypes = $this->getRelation('websiteType', ['type']);

        // $ui->image = $ui->getFirstMediaUrl('image');

        return response()->json([
            'data' => $template,
            'websiteTypes' => $websiteTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateRequest $request, Template $template)
    {
        $validated = $request->validated();
        // unset($validated['image']);

        $template->fill($validated);

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //    $ui->clearMediaCollection('image');
        //    $ui->addMediaFromRequest('image')
        //        ->toMediaCollection('image');
        // }

        $template->save();

        return redirect()->route('dashboard.templates.index')->with('message', 'template updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:templates,id',
        ]);

        Template::destroy($validated['ids']);

        return redirect()->back()->with('message', __('template(s) deleted successfully.'));
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
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

        return redirect()->back()->with('message', $message);
    }
}
