<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Category;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Pages\Categories\UpdateCategoryRequest;

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

        return $this->inertiaRender('dashboard/pages/categories/Categories', ['categories' => $data]);
    }

    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);
            $parents = Category::active()->get();

            return $this->jsonSuccess('', [
                'websites' => $websites,
                'parents' => $parents,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CategoriesController@create', $e, 'An error when fetching the create page');
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = new Category($validated);
            $category->save();

            return $this->redirectSuccess('dashboard.categories.index', 'Category created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@store', $e, 'An error occurred while creating the category');
        }
    }

    public function show(string $id)
    {
        try {
            $query = Category::with(['website', 'parent'])->findOrFail($id);
            $category = $this->flattenRelationData($query, ['website_name', 'parent_name']);

            return $this->jsonSuccess('', [
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CategoriesController@show', $e, 'An error when fetching the show page');
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

            return $this->jsonSuccess('', [
                'data' => $category,
                'websites' => $websites,
                'parents' => $parents,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CategoriesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $validated = $request->validated();

            $category->fill($validated);
            $category->save();

            return $this->redirectSuccess('dashboard.categories.index', 'Category updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@update', $e, 'An error occurred while updating the category');
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

            return $this->backSuccess('Category(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@destroy', $e, 'An error occurred while deleting the category(s)');
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

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@toggleActive', $e, 'An error occurred while updating the category status');
        }
    }
}
