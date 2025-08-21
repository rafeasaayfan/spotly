<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Ui;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Template;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Ui\Templates\StoreTemplateRequest;
use App\Http\Requests\Dashboard\Pages\Ui\Templates\UpdateTemplateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        try {
            $websiteTypes = $this->getRelation('websiteType', ['type']);

            return response()->json([
                'websiteTypes' => $websiteTypes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplatesController@create',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateRequest $request)
    {
        try {
            $validated = $request->validated();
            // unset($validated['image']);

            // if ($request->hasFile('image') && $request->file('image')->isValid()) {
            //    $ui->addMediaFromRequest('image')
            //        ->toMediaCollection('image');
            // }

            $template = new Template($validated);
            $template->created_by = Auth::id();

            $template->save();

            return redirect()->route('dashboard.templates.index')->with('message', 'Template created successfully');
        } catch (\Exception $e) {
            Log::error('Error in TemplatesController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the template. Please try again.');
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

            // $ui->image = $ui->getFirstMediaUrl('image');

            return response()->json([
                'data' => $template,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplatesController@show',
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
            $template = Template::findOrFail($id);
            $websiteTypes = $this->getRelation('websiteType', ['type']);
            // $ui->image = $ui->getFirstMediaUrl('image');

            return response()->json([
                'data' => $template,
                'websiteTypes' => $websiteTypes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on TemplatesController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateRequest $request, Template $template)
    {
        try {
            $validated = $request->validated();
            // unset($validated['image']);
            // if ($request->hasFile('image') && $request->file('image')->isValid()) {
            //    $ui->clearMediaCollection('image');
            //    $ui->addMediaFromRequest('image')
            //        ->toMediaCollection('image');
            // }

            $template->fill($validated);
            $template->save();

            return redirect()->route('dashboard.templates.index')->with('message', 'Template updated successfully');
        } catch (\Exception $e) {
            Log::error('Error on TemplatesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the template. Please try again.');
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

            return redirect()->back()->with('message', __('Template(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error on TemplatesController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
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
        } catch (\Exception $e) {
            Log::error('Error in TemplatesController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the template status. Please try again.');
        }
    }
}
