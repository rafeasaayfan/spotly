<?php

namespace App\Http\Controllers\Websites\Restaurant\Dashboard\Pages;

use App\Enums\Websites\Ecommerce\OrderStatus;
use App\Http\Controllers\Websites\BaseController;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Orders\UpdateOrderRequest;
use App\Models\Country;
use App\Models\EcommerceTrackOrder;
use App\Models\RestaurantOrder;
use App\Services\Websites\Ecommerce\Dashboard\OrdersService;
use Illuminate\Validation\Rule;

class OrdersController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RestaurantOrder::where('website_id', $this->website->id);
        $cities = config('cities.lebanon');

        $columnsSearching = ['order_number', 'user.name'];
        $columnsSelection = ['id', 'website_user_id', 'order_number', 'total_amount', 'delivery_address', 'city', 'phone_number', 'status', 'status_changed_at'];
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
            $query = RestaurantOrder::where('website_id', $this->website->id)
                ->with(['items', 'items.menuItem:id,name', 'user:id,name,email,phone_number'])
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
            $order = RestaurantOrder::where('website_id', $this->website->id)
                ->select(['id', 'delivery_address', 'city', 'phone_number', 'status_reason', 'note', 'status'])
                ->findOrFail($id);

            $cities = config('cities.lebanon');
            $countries = Country::active()->get();

            return $this->jsonSuccess('', [
                'data' => $order,
                'cities' => $cities,
                'countries' => $countries
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('OrdersController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, RestaurantOrder $order)
    {
        if ($order->website_id !== $this->website->id) return;

        try {
            $validated = $request->validated();

            $order->fill($validated);
            if ($validated['status_reason']) {
                $track = EcommerceTrackOrder::where('order_id', $order->id)
                    ->where('status', $order->status)
                    ->orderByDesc('id')
                    ->firstOrFail();

                $track->update([
                    'status_reason' => $validated['status_reason'],
                ]);
            }
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

            RestaurantOrder::destroy($validated['ids']);

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
            'status' => ['required', 'string', Rule::enum(OrderStatus::class)],
        ]);

        try {
            $result = OrdersService::changeStatus(
                $this->website->id,
                $this->website->name,
                $this->website->email,
                $this->website->email_verified_at ? true : false,
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
