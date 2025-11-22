<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceOrder;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Orders\UpdateOrderRequest;
use App\Jobs\Websites\Ecommerce\SendOrderEmailJob;
use App\Models\WebsiteUser;

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
        $allowedTransitions = [
            'pending'   => ['confirmed', 'rejected'],
            'confirmed' => ['delivered', 'cancelled'],
            'delivered' => ['refunded'],
        ];

        try {
            $validated = $request->validate([
                'status' => 'required|string|in:pending,confirmed,delivered,cancelled,refunded',
            ]);
            $order = EcommerceOrder::where('website_id', $this->website->id)->with(['items.product.variants'])->findOrFail($id);

            $currentStatus = $order->status;
            $newStatus = $validated['status'];

            if (! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
                return $this->backError("You cannot change the order status from {$currentStatus} to {$newStatus}");
            }

            $order->update([
                'status' => $newStatus,
                'status_changed_at' => now()
            ]);

            $this->updateInventoryQuantities($order, $newStatus);

            $message = match ($validated['status']) {
                'confirmed' => 'Order confirmed successfully.',
                'delivered' => 'Order marked as delivered.',
                'cancelled' => 'Order cancelled successfully.',
                'refunded'  => 'Order refunded successfully.',
                default     => 'Order status updated successfully.',
            };

            if (in_array($newStatus, ['confirmed', 'cancelled', 'rejected', 'delivered'])) {
                if ($order->website_user_id !== null) {
                    $this->callTheOrderJob($order, $newStatus);
                }
            }

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('OrdersController@changeStatus', $e, 'An error occurred while updating the Order status');
        }
    }

    /**
     * Update product variant inventory quantities based on the new order status.
     */
    protected function updateInventoryQuantities(EcommerceOrder $order, string $newStatus)
    {
        foreach ($order->items as $item) {
            $variant = $item->product->variants
                ->firstWhere('color', $item->color);

            if (!$variant) continue;

            $qty = $item->quantity;

            match ($newStatus) {
                'confirmed' => $variant->increment('reserved_quantity', $qty),

                'delivered' => $variant->update([
                    'stock_quantity' => max($variant->stock_quantity - $qty, 0),
                    'reserved_quantity' => max($variant->reserved_quantity - $qty, 0),
                ]),

                'cancelled' => $variant->update([
                    'reserved_quantity' => max($variant->reserved_quantity - $qty, 0),
                ]),

                'refunded' => $variant->increment('stock_quantity', $qty),

                default => null,
            };
        }
    }

    /**
     * Update product variant inventory quantities based on the new order status.
     */
    protected function callTheOrderJob(EcommerceOrder $order, string $newStatus)
    {
        $orderOwner = WebsiteUser::where('website_id', $this->website->id)
            ->where('id', $order->website_user_id)
            ->first();
        $orderOwnerEmail = $orderOwner?->email;

        SendOrderEmailJob::dispatch(
            $order->order_number,
            $newStatus,
            $this->website->id,
            $this->website->name,
            $this->website->email,
            $this->website->subdomain,
            $orderOwnerEmail ?? null
        );
    }
}
