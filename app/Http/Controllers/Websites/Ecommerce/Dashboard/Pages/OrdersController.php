<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\EcommerceOrder;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Orders\UpdateOrderRequest;

class OrdersController extends Controller
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
        $query = EcommerceOrder::where('website_id', $this->website->id);
        $cities = config('cities.lebanon');

        $columnsSearching = ['order_number', 'user.name'];
        $columnsSelection = ['id', 'website_user_id', 'order_number', 'total_amount', 'delivery_address', 'city', 'status', 'status_changed_at'];
        $relations = ['user_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return $this->inertiaRender(
            'pages/orders/Orders',
            [
                'orders' => $data,
                'cities' => $cities,
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
            $query = EcommerceOrder::where('website_id', $this->website->id)
                ->with(['items', 'items.product:id,name'])
                ->findOrFail($id);
            $order = $this->flattenRelationData($query, ['websiteUser_name', 'paymentMethod_name', 'deliveryFee_amount']);

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
        if($order->website_id !== $this->website->id) return;

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
        $allowedTransitions = [
            'pending'   => ['confirmed'],
            'confirmed' => ['delivered', 'cancelled'],
            'delivered' => ['refunded'],
        ];

        try {
            $validated = $request->validate([
                'status' => 'required|string|in:pending,confirmed,delivered,cancelled,refunded',
            ]);
            $order = EcommerceOrder::where('website_id', $this->website->id)->findOrFail($id);

            $currentStatus = $order->status;
            $newStatus = $validated['status'];

            if (! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
                return $this->backError("You cannot change the order status from {$currentStatus} to {$newStatus}");
            }

            $order->update(['status' => $newStatus]);

            $message = match ($validated['status']) {
                'confirmed' => 'Order confirmed successfully.',
                'delivered' => 'Order marked as delivered.',
                'cancelled' => 'Order cancelled successfully.',
                'refunded'  => 'Order refunded successfully.',
                default     => 'Order status updated successfully.',
            };

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('OrdersController@changeStatus', $e, 'An error occurred while updating the Order status');
        }
    }
}
