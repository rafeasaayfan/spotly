<?php

namespace App\Http\Controllers\Websites\Restaurant\Dashboard\Pages;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Restaurant\Dashboard\Pages\Menus\StoreMenuItemRequest;
use App\Http\Requests\Websites\Restaurant\Dashboard\Pages\Menus\UpdateMenuItemRequest;
use App\Jobs\Websites\Restaurant\CreateProductSlugJob;
use App\Models\Category;
use App\Models\RestaurantMenuItem;
use App\Models\RestaurantMenuItemGroup;
use App\Services\Websites\Restaurant\Dashboard\MenuItemsService;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuItemsController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RestaurantMenuItem::where('website_id', $this->website->id);

        $columnsSearching = ['name'];
        $columnsSelection = ['id', 'category_id', 'name', 'price', 'is_discount', 'is_special', 'is_in_home', 'is_active', 'created_at'];
        $relations = ['category_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender(
            'pages/menuItems/MenuItems',
            [
                'menuItems' => $data,
                'websiteNameAndLogo' => $this->websiteNameAndLogo()
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
            $menuGroups = RestaurantMenuItemGroup::active()->get();

            $categories = Category::where('website_id', $this->website->id)->active()->get();

            return $this->inertiaRender(
                'pages/menuItems/actions/Create',
                [
                    'websiteNameAndLogo' => $this->websiteNameAndLogo(),
                    'menuGroups' => $menuGroups,
                    'categories' => $categories
                ],
                true,
                true
            );
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@create', $e, 'An error occurred while creating the Product');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request)
    {
        if ($request->input('step') === 1) {
            return;
        }

        try {
            $validated = $request->validated();
            $validatedOptions = $validated['group_options'];
            $images = $validated['restaurant_item_images'];
            unset($validated['group_options']);
            unset($validated['restaurant_item_images']);

            DB::beginTransaction();

            $menuItem = RestaurantMenuItem::create([
                ...$validated,
                'website_id' => $this->website->id,
                'slug' => $this->website->id . '-slug',
            ]);

            MenuItemsService::handleItemOptions($menuItem, $validatedOptions, 'create');

            DB::commit();

            $menuItem->storeMediaImages($images, 'restaurant_item_images');

            CreateProductSlugJob::dispatch($menuItem)->afterCommit();

            return $this->redirectSuccess('dashboard.menuItems.index', 'Menu Item created successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@store', $e, 'An error occurred while creating the Product');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = RestaurantMenuItem::where('website_id', $this->website->id)
                ->with(['options'])
                ->findOrFail($id);
            $menuItems = $this->flattenRelationData($query, ['category_name']);

            $menuGroups = RestaurantMenuItemGroup::active()->get();

            return $this->inertiaRender(
                'pages/menuItems/actions/View',
                [
                    'websiteNameAndLogo' => $this->websiteNameAndLogo(),
                    'data' => $menuItems,
                    'menuGroups' => $menuGroups,
                ],
                true,
                true
            );
        } catch (\Exception $e) {
            return $this->logJsonResponse('MenuItemsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $menuItem = RestaurantMenuItem::where('website_id', $this->website->id)
                ->with(['options'])
                ->findOrFail($id);

            $menuGroups = RestaurantMenuItemGroup::active()->get();

            $categories = Category::where('website_id', $this->website->id)->active()->get();

            return $this->inertiaRender(
                'pages/menuItems/actions/Edit',
                [
                    'data' => $menuItem,
                    'websiteNameAndLogo' => $this->websiteNameAndLogo(),
                    'menuGroups' => $menuGroups,
                    'categories' => $categories
                ],
                true,
                true
            );
        } catch (\Exception $e) {
            return $this->logJsonResponse('MenuItemsController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request, RestaurantMenuItem $menuItem)
    {
        if ($menuItem->website_id !== $this->website->id) return;

        if ($request->input('step') === 1) {
            return;
        }

        try {
            $validated = $request->validated();
            $validatedOptions = $validated['group_options'];
            $images = $validated['restaurant_item_images'];
            unset($validated['group_options']);
            unset($validated['restaurant_item_images']);

            $oldName = $menuItem->name;

            DB::beginTransaction();

            $data = array_merge([
                'is_in_home' => $menuItem->is_active ? ($validated['is_active'] ? $menuItem->is_in_home : false) : false,
                'is_special' => $menuItem->is_active ? ($validated['is_active'] ? $menuItem->is_special : false) : false,
            ], $validated);

            $menuItem->update($data);

            MenuItemsService::handleItemOptions($menuItem, $validatedOptions, 'update');

            DB::commit();

            $menuItem->updateMediaImages($images, 'restaurant_item_images');

            if ($validated['name'] !== $oldName) {
                CreateProductSlugJob::dispatch($menuItem)->afterCommit();
            }

            return $this->redirectSuccess('dashboard.menuItems.index', 'Menu Item updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@update', $e, 'An error occurred while updating the Product');
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
                'ids.*' => 'integer|exists:restaurant_menu_items,id,website_id,' . $this->website->id,
            ]);

            RestaurantMenuItem::whereIn('id', $validated['ids'])
                ->chunkById(20, function ($menuItems) {
                    foreach ($menuItems as $menuItem) {
                        $menuItem->delete();
                    }
                });

            return $this->backSuccess('Menu Item(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@destroy', $e, 'An error occurred while deleting the Product(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $menuItem = RestaurantMenuItem::where('website_id', $this->website->id)->findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $menuItem->update([
                'is_active' => $validated['is_active'],
                'is_in_home' => $validated['is_active'] ? $menuItem->is_in_home : false,
                'is_special' => $validated['is_active'] ? $menuItem->is_special : false,
            ]);

            $message = $validated['is_active']
                ? 'Menu item activated successfully.'
                : 'Menu item deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@toggleActive', $e, 'An error occurred while updating the Product status');
        }
    }

        /**
     * Toggle the "is_in_home" status of the specified resource.
     */
    public function toggleIsInHome(Request $request, $id)
    {
        try {
            $product = RestaurantMenuItem::where('website_id', $this->website->id)->findOrFail($id);

            if (!$product->is_active) return $this->backError('Please activate the item first');

            $validated = $request->validate([
                'is_in_home' => 'required|boolean',
            ]);

            if ($validated['is_in_home'] && $product->is_special) {
                $count = RestaurantMenuItem::where('website_id', $this->website->id)
                    ->active()->special()->inHome()->count();

                if ($count >= 6) return $this->backError('Max 6 special items allowed on home');
            }

            if ($validated['is_in_home'] && !$product->is_special) {
                $count = RestaurantMenuItem::where('website_id', $this->website->id)
                    ->active()->inHome()->where('is_special', false)->count();

                if ($count >= 8) return $this->backError('Max 8 items allowed on home');
            }

            $product->update([
                'is_in_home' => $validated['is_in_home'],
            ]);

            $message = $validated['is_in_home']
                ? 'Item added to home successfully.'
                : 'Item removed from home successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@toggleActive', $e, 'An error occurred while updating the menu item status');
        }
    }

    /**
     * Toggle the "is_special" status of the specified resource.
     */
    public function toggleIsSpecial(Request $request, $id)
    {
        try {
            $product = RestaurantMenuItem::where('website_id', $this->website->id)->findOrFail($id);

            if (!$product->is_active) return $this->backError('Please activate the item first');

            $validated = $request->validate([
                'is_special' => 'required|boolean',
            ]);

            if ($validated['is_special'] && $product->is_in_home) {
                $count = RestaurantMenuItem::where('website_id', $this->website->id)
                    ->active()->special()->inHome()->count();

                if ($count >= 6) return $this->backError('Max 6 special items allowed on home');
            }

            if (!$validated['is_special'] && $product->is_in_home) {
                $count = RestaurantMenuItem::where('website_id', $this->website->id)
                    ->active()->inHome()->where('is_special', false)->count();

                if ($count >= 2) {
                    $product->update([
                        'is_in_home' => false,
                    ]);
                }
            }

            $product->update([
                'is_special' => $validated['is_special'],
            ]);

            $message = $validated['is_special']
                ? 'Item marked as special successfully.'
                : 'Item unmarked as special successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@toggleActive', $e, 'An error occurred while updating the menu item status');
        }
    }

    /**
     * Toggle the "is_discount" status of the specified resource.
     */
    public function toggleIsDiscount(Request $request, $id)
    {
        try {
            $product = RestaurantMenuItem::where('website_id', $this->website->id)->findOrFail($id);

            $validated = $request->validate([
                'is_discount' => 'required|boolean',
            ]);

            if ($product->discount_price <= 0 && $validated['is_discount']) {
                return $this->backError('Please set the discount price first', 'warning');
            }

            $product->update([
                'is_discount' => $validated['is_discount'],
            ]);

            $message = $validated['is_discount'] ? 'Item marked as discount successfully.' : 'Item unmarked as discount successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('MenuItemsController@toggleIsDiscount', $e, 'An error occurred while updating the menu item discount status');
        }
    }
}
