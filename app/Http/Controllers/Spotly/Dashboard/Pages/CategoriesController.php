<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Category;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Pages\Categories\UpdateCategoryRequest;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Category::query();

        $columnsSearching = ['website.name', 'name'];
        $columnsSelection = ['id', 'website_id', 'parent_id', 'name', 'description', 'is_active', 'created_at'];
        $relations = ['website_name', 'parent_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/categories/Categories', [
            'categories' => $data,
        ]);
    }

    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);
            $parents = Category::active()->get();

            return response()->json([
                'websites' => $websites,
                'parents' => $parents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on CategoriesController@create',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = new Category($validated);
            $category->save();

            return redirect()->route('dashboard.categories.index')->with('message', 'Category created successfully');
        } catch (\Exception $e) {
            Log::error('Error in CategoriesController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the category. Please try again.');
        }
    }

    public function show(string $id)
    {
        try {
            $query = Category::with(['website', 'parent'])->findOrFail($id);
            $category = $this->flattenRelationData($query, ['website_name', 'parent_name']);

            return response()->json([
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on CategoriesController@show',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);

            $websites = $this->getRelation('website', ['name']);

            $childrenIds = Category::where('parent_id', $category->id)->pluck('id')->toArray();
            $parents = Category::active()
                ->where('id', '!=', $category->id)
                ->whereNotIn('id', $childrenIds)
                ->get();

            return response()->json([
                'data' => $category,
                'websites' => $websites,
                'parents' => $parents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on CategoriesController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $validated = $request->validated();

            $category->fill($validated);
            $category->save();

            return redirect()->route('dashboard.categories.index')->with('message', 'Category updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in CategoriesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the category. Please try again.');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:categories,id',
            ]);

            Category::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Category(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in CategoriesController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the category(s). Please try again.');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $category->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Category activated successfully.'
                : 'Category deactivated successfully.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in CategoriesController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the category status. Please try again.');
        }
    }
}
