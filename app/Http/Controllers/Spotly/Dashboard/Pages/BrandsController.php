<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Brand;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Brands\StoreBrandRequest;
use App\Http\Requests\Dashboard\Pages\Brands\UpdateBrandRequest;

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

        return $this->inertiaRender('dashboard/pages/brands/Brands', ['brands' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);

            return $this->jsonSuccess('', ['websites' => $websites]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('BrandsController@create', $e, 'An error when fetching the create page');
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

            return $this->redirectSuccess('dashboard.brands.index', 'Brand created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@store', $e, 'An error occurred while creating the brand');
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

            return $this->jsonSuccess('', [
                'data' => $brand,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('BrandsController@show', $e, 'An error when fetching the show page');
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

            return $this->jsonSuccess('', [
                'data' => $brand,
                'websites' => $websites,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('BrandsController@edit', $e, 'An error when fetching the edit page');
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

            return $this->redirectSuccess('dashboard.brands.index', 'Brand updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@update', $e, 'An error occurred while updating the brand');
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

            return $this->backSuccess('Brand(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@destroy', $e, 'An error occurred while deleting the brand(s)');
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

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@toggleActive', $e, 'An error occurred while updating the brand status');
        }
    }
}
