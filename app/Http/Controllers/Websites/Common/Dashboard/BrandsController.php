<?php

namespace App\Http\Controllers\Websites\Common\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Common\Dashboard\Brands\StoreBrandRequest;
use App\Http\Requests\Websites\Common\Dashboard\Brands\UpdateBrandRequest;

class BrandsController extends Controller
{
    use DataTableTrait;

    public $website;

    public function __construct()
    {
        $this->website = app('website');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brand::where('website_id', $this->website->id);

        $columnsSearching = ['name'];
        $columnsSelection = ['id', 'name', 'description', 'is_active'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection);

        return $this->inertiaRender('pages/brands/Brands', ['brands' => $data], true, true);
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
    public function store(StoreBrandRequest $request)
    {
        try {
            $validated = $request->validated();

            $brand = new Brand($validated);
            $brand->website_id = $this->website->id;
            $brand->save();

            return $this->redirectSuccess('dashboard.brands.index', 'Brand created successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@store', $e, 'An error occurred while creating the Brand');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $brand = Brand::where('website_id', $this->website->id)->findOrFail($id);
            
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
            $brand = Brand::where('website_id', $this->website->id)->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $brand,
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

            if($brand->website_id !== $this->website->id) {
                return $this->backSuccess('Error while updating');
            }

            $brand->fill($validated);
            $brand->save();

            return $this->redirectSuccess('dashboard.brands.index', 'Brand updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@update', $e, 'An error occurred while updating the Brand');
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
                'ids.*' => 'integer|exists:brands,id,website_id,' . $this->website->id,
            ]);
    
            Brand::destroy($validated['ids']);
    
            return $this->backSuccess('Brand(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('BrandsController@destroy', $e, 'An error occurred while deleting the Brand(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
    */
    public function toggleActive(Request $request, $id)
    {
        try {
            $brand = Brand::where('website_id', $this->website->id)->findOrFail($id);
    
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
            return $this->logResponse('BrandsController@toggleActive', $e, 'An error occurred while updating the Brand status');
        }
    }
}
