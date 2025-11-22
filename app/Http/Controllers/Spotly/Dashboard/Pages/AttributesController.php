<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataTableTrait;
use App\Http\Requests\Dashboard\Pages\Attributes\StoreAttributeRequest;
use App\Http\Requests\Dashboard\Pages\Attributes\UpdateAttributeRequest;
use App\Models\EcommerceProductAttribute;
use App\Models\Website;

class AttributesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EcommerceProductAttribute::query();

        $columnsSearching = ['website.name', 'name'];
        $relations = ['website_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, [], $relations);

        return $this->inertiaRender(
            'dashboard/pages/attributes/Attributes',
            [
                'attributes' => $data,
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $websites = Website::select(['id', 'name'])->get();
        $attributes = config('ecommerce_attributes.attributes');

        return $this->jsonSuccess('',
            [
                'websites' => $websites,
                'attributes' => $attributes,
            ],
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request)
    {
        try {
            $validated = $request->validated();

            $attribute = new EcommerceProductAttribute($validated);
            $attribute->type = $validated['values'] ? 'select' : 'text';
            $attribute->save();

            return $this->redirectSuccess('dashboard.attributes.index', 'Attribute created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('AttributesController@store', $e, 'An error occurred while creating the Attribute');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $attribute = EcommerceProductAttribute::findOrFail($id);
            $result = $this->flattenRelationData($attribute, ['website_name']);

            return $this->jsonSuccess('', [
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('AttributesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $attribute = EcommerceProductAttribute::findOrFail($id);
            $websites = $this->getRelation('website', ['name']);

            $attributes = config('ecommerce_attributes.attributes');

            return $this->jsonSuccess('', [
                'data' => $attribute,
                'websites' => $websites,
                'attributes' => $attributes,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('AttributesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, EcommerceProductAttribute $attribute)
    {
        try {
            $validated = $request->validated();

            $attribute->fill($validated);
            $attribute->type = $validated['values'] ? 'select' : 'text';
            $attribute->save();

            return $this->redirectSuccess('dashboard.attributes.index', 'Attribute updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('AttributesController@update', $e, 'An error occurred while updating the Attribute');
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
                'ids.*' => 'integer|exists:ecommerce_product_attributes,id',
            ]);

            EcommerceProductAttribute::destroy($validated['ids']);

            return $this->backSuccess('Attribute(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('AttributesController@destroy', $e, 'An error occurred while deleting the Attribute(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $attribute = EcommerceProductAttribute::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $attribute->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Attribute activated successfully.'
                : 'Attribute deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('AttributesController@toggleActive', $e, 'An error occurred while updating the Attribute status');
        }
    }

    /**
     * Toggle the required status of the specified resource.
     */
    public function toggleRequired(Request $request, $id)
    {
        try {
            $attribute = EcommerceProductAttribute::findOrFail($id);

            $validated = $request->validate([
                'is_required' => 'required|boolean',
            ]);

            $attribute->update([
                'is_required' => $validated['is_required'],
            ]);

            $message = $validated['is_required']
                ? 'Attribute marked as required successfully.'
                : 'Attribute marked as optional successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('AttributesController@toggleRequired', $e, 'An error occurred while updating the Attribute required status');
        }
    }
}
