<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Brand;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Brands\StoreBrandRequest;
use App\Http\Requests\Dashboard\Pages\Brands\UpdateBrandRequest;
use Illuminate\Support\Facades\Log;

class BrandsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Brand::query();

        $columnsSearching = ['website.name', 'name'];
        $columnsSelection = [];
        $relations = ['website_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/brands/Brands', [
            'brands' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);

            return response()->json([
                'websites' => $websites,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on BrandsController@create',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        try {
            $validated = $request->validated();

            $brand = new Brand($validated);

            $brand->save();

            return redirect()->route('dashboard.brands.index')->with('message', 'Brand created successfully');

        } catch (\Exception $e) {
            Log::error('Error in BrandsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the brand. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = Brand::with('website')->findOrFail($id);

            $brand = $this->flattenRelationData($query, ['website_name']);

            return response()->json([
                'data' => $brand,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on BrandsController@show',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $brand = Brand::findOrFail($id);

            $websites = $this->getRelation('website', ['name']);

            return response()->json([
                'data' => $brand,
                'websites' => $websites,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on BrandsController@edit',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        try {
            $validated = $request->validated();

            $brand->fill($validated);

            $brand->save();

            return redirect()->route('dashboard.brands.index')->with('message', 'Brand updated successfully');

        } catch (\Exception $e) {
            Log::error('Error in BrandsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the brand. Please try again.');
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
                'ids.*' => 'integer|exists:brands,id',
            ]);
    
            Brand::destroy($validated['ids']);
    
            return redirect()->back()->with('message', __('Brand(s) deleted successfully.'));

        } catch (\Exception $e) {
            Log::error('Error in BrandsController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the brand(s). Please try again.');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
    
            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);
    
            $brand->update([
                'is_active' => $validated['is_active'],
            ]);
    
            $message = $validated['is_active']
                ? 'Brand activated successfully.'
                : 'Brand deactivated successfully.';
    
            return redirect()->back()->with('message', $message);
            
        } catch (\Exception $e) {
            Log::error('Error in BrandsController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the brand status. Please try again.');
        }
    }
}
