<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Http\Requests\Websites\Ecommerce\OrderRequest;
use App\Models\EcommerceOrder;
use App\Services\Websites\Ecommerce\ProductService;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{
    /**
     * Display the orders detail page.
     */
    public function index(OrderRequest $request)
    {
        $productService = new ProductService($this->website->id);
        $orders = $productService->getOrderProducts($request->validated());

        return $this->inertiaRender('pages/orders/Orders', [
            'orders' => $orders,
            'colors' => $this->websiteTemplate()->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'iniCartItemsCount' => $this->cartItems()->count(),
        ], true);
    }

    /**
     * Cancel the specified order.
     */
    public function cancelOrder(EcommerceOrder $order)
    {
        if ($order->website_id !== $this->website->id) {
            return $this->backError('Unauthorized access');
        }

        $order->update([
            'status' => 'cancelled',
            'status_changed_at' => now(),
        ]);

        return $this->redirectSuccess(route: 'orders', message: __('messages.order_cancelled'), forWebsite: true);
    }

    /**
     * Display the track order page.
     */
    public function trackOrder(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'nullable|string',
        ]);

        $order = null;
        if (isset($validated['order_number'])) {
            $order = EcommerceOrder::where('website_id', $this->website->id)
                ->where('order_number', $validated['order_number'])
                ->with(['user:id,name', 'items.product:id,slug,name', 'trackOrder' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                }])
                ->first();
        }

        return $this->inertiaRender('pages/TrackOrder', [
            'order' => $order,
            'colors' => $this->websiteTemplate()->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'cartItemsCount' => $this->cartItems()->count(),
        ], true);
    }
}
