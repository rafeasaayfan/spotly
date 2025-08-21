<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\WebsiteType;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\WebsiteTypes\StoreWebsiteTypeRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteTypes\UpdateWebsiteTypeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebsiteTypesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
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
        try {
            $validated = $request->validated();

            $created_by_id = Auth::id();
            if (!$created_by_id) {
                return redirect()->back()->withErrors('Unauthorized: User not authenticated.');
            }

            $websiteType = new WebsiteType($validated);
            $websiteType->created_by = $created_by_id;

            $websiteType->save();

            return redirect()->route('dashboard.websiteTypes.index')->with('message', 'Website type created successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsiteTypesController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the Website Type. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = WebsiteType::with('user')->findOrFail($id);
            $websiteType = $this->flattenRelationData($result, ['user_name']);

            return response()->json([
                'data' => $websiteType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching Website Type details',
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
            $websiteType = WebsiteType::findOrFail($id);

            return response()->json([
                'data' => $websiteType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching Website Type for edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteTypeRequest $request, WebsiteType $websiteType)
    {
        try {
            $validated = $request->validated();

            $websiteType->fill($validated);

            $websiteType->save();

            return redirect()->route('dashboard.websiteTypes.index')->with('message', 'Website type updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsiteTypesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the Website Type. Please try again.');
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
                'ids.*' => 'integer|exists:website_types,id',
            ]);

            WebsiteType::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Website type(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in WebsiteTypesController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the Website Type(s). Please try again.');
        }
    }

    // Toggle active status
    public function toggleActive(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in WebsiteTypesController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the Website Type status. Please try again.');
        }
    }
}
