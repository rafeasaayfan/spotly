<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Pages\Categories\UpdateCategoryRequest;
use App\Models\Website;

class CategoriesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $websites = $this->getRelation('website', ['name']);
        $parents = Category::active()->get();

        return response()->json([
            'websites' => $websites,
            'parents' => $parents,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = new Category($validated);
        $category->save();

        return redirect()->route('dashboard.categories.index')->with('message', 'Category created successfully');
    }

    public function show(string $id)
    {
        $query = Category::with(['website', 'parent'])->findOrFail($id);
        $category = $this->flattenRelationData($query, ['website_name', 'parent_name']);

        return response()->json([
            'data' => $category,
        ]);
    }

    public function edit(string $id)
    {
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
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $category->fill($validated);
        $category->save();

        return redirect()->route('dashboard.categories.index')->with('message', 'Category updated successfully');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:categories,id',
        ]);
        Category::destroy($validated['ids']);
        return redirect()->back()->with('message', __('Category(s) deleted successfully.'));
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
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
    }
}
