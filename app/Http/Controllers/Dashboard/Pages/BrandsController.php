<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Brands\StoreBrandRequest;
use App\Http\Requests\Dashboard\Pages\Brands\UpdateBrandRequest;

class BrandsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $websites = $this->getRelation('website', ['name']);

        return response()->json([
            'websites' => $websites,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $validated = $request->validated();

        $brand = new Brand($validated);

        $brand->save();

        return redirect()->route('dashboard.brands.index')->with('message', 'Brand created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = Brand::with('website')->findOrFail($id);
        $brand = $this->flattenRelationData($query, ['website_name']);

        return response()->json([
            'data' => $brand,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        $websites = $this->getRelation('website', ['name']);

        return response()->json([
            'data' => $brand,
            'websites' => $websites,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $validated = $request->validated();

        $brand->fill($validated);

        $brand->save();

        return redirect()->route('dashboard.brands.index')->with('message', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:brands,id',
        ]);

        Brand::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Brand(s) deleted successfully.'));
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
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
    }
}
