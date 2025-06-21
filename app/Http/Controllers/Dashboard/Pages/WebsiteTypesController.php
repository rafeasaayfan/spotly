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

        $columnsSearching = ['type', 'user_name'];
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
        return Inertia::render('dashboard/pages/websiteTypes/actions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteTypeRequest $request)
    {
        $validated = $request->validated();

        $websiteType = new WebsiteType($validated);

        $websiteType->created_by = Auth::user()->id;

        $websiteType->save();

        return redirect()->route('dashboard.websiteTypes.index')->with('message', 'WebsiteType created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $websiteType = WebsiteType::findOrFail($id);

        return Inertia::render('dashboard/pages/websiteTypes/actions/View', [
            'data' => $websiteType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $websiteType = WebsiteType::findOrFail($id);

        return Inertia::render('dashboard/pages/websiteTypes/actions/Edit', [
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

        $websiteType->updated_by = Auth::user()->id;

        $websiteType->save();

        return redirect()->route('dashboard.websiteTypes.index')->with('message', 'WebsiteType updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:websiteTypes,id',
        ]);

        WebsiteType::destroy($validated['ids']);

        return redirect()->back()->with('message', __('WebsiteType(s) deleted successfully.'));
    }
}
