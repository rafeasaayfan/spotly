<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Carts\UpdateCartRequest;
use App\Models\EcommerceCart;

class CartsController extends Controller
{
    use DataTableTrait;

    public $website;

    public function __construct()
    {
        $this->website = app('website')->load('media');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EcommerceCart::where('website_id', $this->website->id)->withCount('items');

        $columnsSearching = ['websiteUser.name', 'expires_at'];
        $columnsSelection = ['id', 'website_user_id', 'expires_at', 'status', 'created_at'];
        $relations = ['websiteUser_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return $this->inertiaRender(
            'pages/carts/Carts',
            [
                'carts' => $data,
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
            $query = EcommerceCart::where('website_id', $this->website->id)
            ->with(['items', 'items.product:id,name'])->withCount('items')
            ->findOrFail($id);
            $cart = $this->flattenRelationData($query, ['websiteUser_name']);

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
    public function update($request, EcommerceCart $cart)
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
                'ids.*' => 'integer|exists:carts,id,website_id,' . $this->website->id,
            ]);
    
            EcommerceCart::destroy($validated['ids']);
    
            return $this->backSuccess('Cart(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CartsController@destroy', $e, 'An error occurred while deleting the Cart(s)');
        }
    }
}
