<?php

namespace App\Http\Controllers\Websites\Restaurant\Dashboard\Pages;

use App\Http\Controllers\Websites\BaseController;
use App\Models\RestaurantCart;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;

class CartsController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RestaurantCart::where('website_id', $this->website->id)->withCount('items');

        $columnsSearching = ['user.name'];
        $columnsSelection = ['id', 'website_user_id', 'status', 'created_at'];
        $relations = ['user_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender(
            'pages/carts/Carts',
            [
                'carts' => $data,
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = RestaurantCart::where('website_id', $this->website->id)
                ->with(['items', 'items.menuItem:id,name'])->withCount('items')
                ->findOrFail($id);
            $cart = $this->flattenRelationData($query, ['user_name']);

            return $this->jsonSuccess('', [
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CartsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, RestaurantCart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:restaurant_carts,id',
            ]);

            RestaurantCart::destroy($validated['ids']);
    
            return $this->backSuccess('Cart(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CartsController@destroy', $e, 'An error occurred while deleting the Cart(s)');
        }
    }
}
