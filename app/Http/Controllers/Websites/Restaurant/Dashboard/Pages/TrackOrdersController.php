<?php

namespace App\Http\Controllers\Websites\Restaurant\Dashboard\Pages;

use App\Http\Controllers\Websites\BaseController;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Models\RestaurantTrackOrder;

class TrackOrdersController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RestaurantTrackOrder::where('website_id', $this->website->id);

        $columnsSearching = ['order.order_number'];
        $columnsSelection = [];
        $relations = ['order_order_number'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender(
            'pages/trackOrders/TrackOrders',
            [
                'trackOrders' => $data,
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
    public function store(RestaurantTrackOrder $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cart = RestaurantTrackOrder::where('website_id', $this->website->id)
                ->with(['order', 'user:id,name,email,phone_number'])
                ->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('TrackOrdersController@show', $e, 'An error when fetching the show page');
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
    public function update($request, RestaurantTrackOrder $trackorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
    }
}