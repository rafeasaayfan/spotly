<?php

namespace App\Http\Controllers\Websites\Ecommerce;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends BaseController
{
    /**
     * Display the orders detail page.
     */
    public function index(Request $request)
    {
        $orders = $this->getOrders($request);

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
     * Get paginated orders for the orders page based on filters (status, search, sort, etc).
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getOrders(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,confirmed,delivered,rejected,cancelled,refunded',
            'search' => 'nullable|string|min:0|max:100',
            'sort_by' => 'nullable|string|in:date,amount',
            'sort_dir' => 'nullable|string|in:asc,desc',
        ]);

        $status = $validated['status'] ?? 'pending';
        $search = isset($validated['search']) ? trim($validated['search']) : null;
        $sort_by = $validated['sort_by'] ?? 'date';
        $sort_dir = $validated['sort_dir'] ?? 'desc';

        $query = EcommerceOrder::where('website_id', $this->website->id)
            ->when(Auth::guard('website')->check(), function ($q) {
                $q->where('website_user_id', Auth::guard('website')->id());
            }, function ($q) {
                $q->where('session_id', session()->getId());
            });

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%");
            });
        }

        if ($sort_by === 'amount') {
            $query->orderBy('total_amount', $sort_dir);
        } else {
            $query->orderBy('created_at', $sort_dir);
        }

        $orders = $query->with(['paymentMethod:id,name', 'items.product:id,name,slug'])->paginate(6);

        return $orders;
    }
}
