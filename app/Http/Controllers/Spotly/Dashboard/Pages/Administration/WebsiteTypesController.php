<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\WebsiteType;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\WebsiteTypes\StoreWebsiteTypeRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteTypes\UpdateWebsiteTypeRequest;
use Illuminate\Support\Facades\Auth;

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

        return $this->inertiaRender('dashboard/pages/administration/websiteTypes/WebsiteTypes', ['websiteTypes' => $data]);
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

            return $this->redirectSuccess('dashboard.websiteTypes.index', 'Website Type created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTypesController@store', $e, 'An error occurred while creating the Website Type');
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

            return $this->jsonSuccess('', [
                'data' => $websiteType,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteTypesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $websiteType = WebsiteType::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $websiteType,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteTypesController@edit', $e, 'An error when fetching the edit page');
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

            return $this->redirectSuccess('dashboard.websiteTypes.index', 'Website Type updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTypesController@update', $e, 'An error occurred while updating the Website Type');
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

            return $this->backSuccess('Website Type(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTypesController@destroy', $e, 'An error occurred while deleting the Website Type(s)');
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

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteTypesController@toggleActive', $e, 'An error occurred while updating the Website Type status');
        }
    }
}
