<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebsiteType;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\WebsiteTypes\StoreWebsiteTypeRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteTypes\UpdateWebsiteTypeRequest;
use Illuminate\Support\Facades\Auth;

class WebsiteTypesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebsiteType::query();

        $columnsSearching = ['user_name', 'type'];
        $columnsSelection = [];
        $relations = ['user_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/websiteTypes/WebsiteTypes', [
            'websiteTypes' => $data,
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
    public function store(StoreWebsiteTypeRequest $request)
    {
        $validated = $request->validated();

        $created_by = Auth::id();
        if (!$created_by) return;

        $websiteType = new WebsiteType($validated);
        $websiteType->created_by = $created_by;

        $websiteType->save();

        return redirect()->route('dashboard.websiteTypes.index')->with('message', 'Website type created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = WebsiteType::with('user')->findOrFail($id);
        $websiteType = $this->flattenRelationData($result, ['user_name']);

        return response()->json([
            'data' => $websiteType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $websiteType = WebsiteType::findOrFail($id);

        return response()->json([
            'data' => $websiteType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteTypeRequest $request, WebsiteType $websiteType)
    {
        $validated = $request->validated();

        $websiteType->fill($validated);

        $websiteType->save();

        return redirect()->route('dashboard.websiteTypes.index')->with('message', 'Website type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:website_types,id',
        ]);

        WebsiteType::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Website type(s) deleted successfully.'));
    }

    // Toggle active status
    public function toggleActive(Request $request, $id)
    {
        $websiteType = WebsiteType::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $websiteType->update([
            'is_active' => $validated['is_active'],
        ]);

        $message = $validated['is_active']
            ? 'Website type activated successfully.'
            : 'Website type deactivated successfully.';

        return redirect()->back()->with('message', $message);
    }
}
