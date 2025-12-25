<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Attributes\StoreAttributeRequest;
use App\Http\Requests\Dashboard\Pages\Attributes\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Website;

class AttributesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attribute::query();

        $columnsSearching = ['website.name', 'name'];
        $relations = ['website_name', 'values_value:attribute_id'];

        $data = $this->dataTable($query, $request, $columnsSearching, [], $relations);

        return $this->inertiaRender(
            'dashboard/pages/attributes/Attributes',
            [
                'attributes' => $data,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = config('attributes.ecommerce_attributes');
        $websites = Website::select(['id', 'name'])->get();

        return $this->jsonSuccess('',
            [
                'attributes' => $attributes,
                'websites' => $websites,
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
            $values = $validated['values'] ?? [];
            unset($validated['values']);

            $attribute = new Attribute($validated);
            $attribute->type = $values || $validated['name'] === 'color' ? 'select' : 'text';
            $attribute->save();

            // Save attribute values if provided
            if (!empty($values)) {
                foreach ($values as $valueData) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $valueData['value'],
                        'value_ar' => $valueData['value_ar'],
                    ]);
                }
            }

            return $this->redirectSuccess('dashboard.attributes.index', 'Attribute created successfully', forWebsite: true);
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
            $query = Attribute::with(['values', 'website:id,name'])
            ->findOrFail($id);
            $attribute = $this->flattenRelationData($query, ['values_value', 'website_name']);

            return $this->jsonSuccess('', [
                'data' => $attribute,
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
            $attribute = Attribute::with(['values'])
            ->findOrFail($id);
            $attributes = config('attributes.ecommerce_attributes');
            $websites = Website::select(['id', 'name'])->get();

            return $this->jsonSuccess('', [
                'data' => $attribute,
                'attributes' => $attributes,
                'websites' => $websites,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('AttributesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        try {
            $validated = $request->validated();
            $values = $validated['values'] ?? [];
            unset($validated['values']);

            $attribute->fill($validated);
            $attribute->type = $values || $validated['name'] === 'color' ? 'select' : 'text';
            $attribute->save();

            if (!empty($values)) {
                $existingIds = collect($values)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                    AttributeValue::where('attribute_id', $attribute->id)
                    ->whereNotIn('id', $existingIds)
                    ->delete();

                foreach ($values as $valueData) {
                    if (isset($valueData['id']) && !empty($valueData['id'])) {
                        AttributeValue::where('id', $valueData['id'])->where('attribute_id', $attribute->id)
                            ->update([
                                'value' => $valueData['value'],
                                'value_ar' => $valueData['value_ar'],
                            ]);
                    } else {
                        AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $valueData['value'],
                            'value_ar' => $valueData['value_ar'],
                        ]);
                    }
                }
            }

            return $this->redirectSuccess('dashboard.attributes.index', 'Attribute updated successfully', forWebsite: true);
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
                'ids.*' => 'integer|exists:attributes,id',
            ]);

            Attribute::destroy($validated['ids']);

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
            $attribute = Attribute::findOrFail($id);

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
            $attribute = Attribute::findOrFail($id);

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
