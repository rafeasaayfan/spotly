<?php

namespace App\Http\Controllers\Websites\Common\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Common\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Websites\Common\Dashboard\Categories\UpdateCategoryRequest;

class CategoriesController extends Controller
{
    use DataTableTrait;

    protected $website;

    public function __construct()
    {
        $this->website = app('website')->load('media');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::where('website_id', $this->website->id);

        $columnsSearching = ['name'];
        $columnsSelection = ['id', 'parent_id', 'name', 'ar_name', 'description', 'is_in_home', 'is_active'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, ['parent_name']);

        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return $this->inertiaRender(
            'pages/categories/Categories',
            [
                'categories' => $data,
                'websiteNameAndLogo' => $websiteNameAndLogo
            ],
            true,
            true
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::active()->where('website_id', $this->website->id)->select(['id', 'name'])->get();

            return $this->jsonSuccess('', [
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CategoriesController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = new Category($validated);
            $category->website_id = $this->website->id;
            $category->save();

            return $this->redirectSuccess('dashboard.categories.index', 'Category created successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@store', $e, 'An error occurred while creating the Category');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::where('website_id', $this->website->id)->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CategoriesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category = Category::where('website_id', $this->website->id)->findOrFail($id);
            
            $descendantIds = $category->allChildrenIds()->toArray();
            $categories = Category::active()
                ->where('website_id', $this->website->id)
                ->whereNotIn('id', array_merge([$id], $descendantIds))
                ->select(['id', 'name'])
                ->get();

            return $this->jsonSuccess('', [
                'data' => $category,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CategoriesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if($category->website_id !== $this->website->id) return;

        try {
            $validated = $request->validated();

            $category->fill($validated);
            $category->is_in_home = $validated['is_active'] ? $category->is_in_home : false;
            $category->save();

            return $this->redirectSuccess('dashboard.categories.index', 'Category updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@update', $e, 'An error occurred while updating the Category');
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
                'ids.*' => 'integer|exists:categories,id,website_id,' . $this->website->id,
            ]);
    
            Category::destroy($validated['ids']);
    
            return $this->backSuccess('Category(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@destroy', $e, 'An error occurred while deleting the Category(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
    */
    public function toggleActive(Request $request, $id)
    {
        try {
            $category = Category::where('website_id', $this->website->id)->findOrFail($id);
    
            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);
    
            $category->update([
                'is_active' => $validated['is_active'],
                'is_in_home' => $validated['is_active'] ? $category->is_in_home : false,
            ]);
    
            $message = $validated['is_active']
                ? 'Category activated successfully'
                : 'Category deactivated successfully';
    
            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@toggleActive', $e, 'An error occurred while updating the Category status');
        }
    }

    /**
     * Toggle the is in home status of the specified resource.
    */
    public function toggleIsInHome(Request $request, $id)
    {
        try {
            $category = Category::where('website_id', $this->website->id)->findOrFail($id);
            if(!$category->is_active) return $this->backError('Please activate the category first');

            $homeCategoriesCount = Category::where('website_id', $this->website->id)
                ->active()->inHome()
                ->count();

            if($homeCategoriesCount === 9) return $this->backError('Max 9 categories allowed on home.');
    
            $validated = $request->validate([
                'is_in_home' => 'required|boolean',
            ]);
    
            $category->update([
                'is_in_home' => $validated['is_in_home'],
            ]);
    
            $message = $validated['is_in_home']
                ? 'Category added to home successfully'
                : 'Category removed from home successfully';
    
            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@toggleIsInHome', $e, 'An error occurred while updating the Category status');
        }
    }
}
