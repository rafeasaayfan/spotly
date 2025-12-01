<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceOrder;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Orders\UpdateOrderRequest;
use App\Jobs\Websites\Ecommerce\SendOrderEmailJob;
use App\Models\WebsiteUser;
use App\Services\Websites\Ecommerce\Dashboard\OrdersService;

class OrdersController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EcommerceOrder::where('website_id', $this->website->id);
        $cities = config('cities.lebanon');

        $columnsSearching = ['order_number', 'user.name'];
        $columnsSelection = ['id', 'website_user_id', 'order_number', 'total_amount', 'delivery_address', 'city', 'status', 'status_changed_at'];
        $relations = ['user_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender(
            'pages/orders/Orders',
            [
                'orders' => $data,
                'cities' => $cities,
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
            $query = EcommerceOrder::where('website_id', $this->website->id)
                ->with(['items', 'items.product:id,name', 'user:id,name,email,phone_number'])
                ->findOrFail($id);
            $order = $this->flattenRelationData($query, ['paymentMethod_name', 'deliveryFee_amount']);

            return $this->jsonSuccess('', [
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('OrdersController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $order = EcommerceOrder::where('website_id', $this->website->id)
                ->select(['id', 'delivery_address', 'city', 'note', 'cancellation_reason', 'status'])
                ->findOrFail($id);
            $cities = config('cities.lebanon');

            return $this->jsonSuccess('', [
                'data' => $order,
                'cities' => $cities
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('OrdersController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, EcommerceOrder $order)
    {
        if ($order->website_id !== $this->website->id) return;

        try {
            $validated = $request->validated();

            $order->fill($validated);
            $order->save();

            return $this->redirectSuccess('dashboard.orders.index', 'Order updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('OrdersController@update', $e, 'An error occurred while updating the Order');
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
                'ids.*' => 'integer|exists:ecommerce_orders,id,website_id,' . $this->website->id,
            ]);

            // $orders = EcommerceOrder::whereIn('id', $validated['ids'])->get();

            // foreach ($orders as $order) {
            //     if ($order->website_user_id !== null) {
            //         $this->callTheOrderJob($order, 'delete');
            //     }
            // }

            EcommerceOrder::destroy($validated['ids']);

            return $this->backSuccess('Order(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('OrdersController@destroy', $e, 'An error occurred while deleting the Order(s)');
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,delivered,cancelled,refunded',
        ]);

        try {
            $result = OrdersService::changeStatus(
                $this->website->id,
                $this->website->name,
                $this->website->email, 
                $this->website->subdomain, 
                $id, 
                $validated['status']
            );

            if (!$result['success']) {
                return $this->backError($result['message']);
            }

            return $this->backSuccess($result['message']);
        } catch (\Exception $e) {
            return $this->logResponse('OrdersController@changeStatus', $e, 'An error occurred while updating the Order status');
        }
    }
}
