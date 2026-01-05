<?php

namespace App\Http\Controllers\Websites\Restaurant\Dashboard;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceOrder;
use App\Models\EcommerceProduct;
use App\Models\WebsiteUser;
use Inertia\Inertia;

class DashboardController extends BaseController
{
    public function index()
    {
        // $userStats = WebsiteUser::where('website_id', $this->website->id)->selectRaw('status, COUNT(*) as count')
        //     ->groupBy('status')
        //     ->pluck('count', 'status');

        // $productStats = EcommerceProduct::where('website_id', $this->website->id)->selectRaw('is_active, COUNT(*) as count')
        //     ->groupBy('is_active')
        //     ->pluck('count', 'is_active');

        // $orderStats = EcommerceOrder::where('website_id', $this->website->id)->selectRaw('status, COUNT(*) as count')
        //     ->groupBy('status')
        //     ->pluck('count', 'status');

        // $paymentStats = EcommerceOrder::where('website_id', $this->website->id)->where('status', 'delivered')
        //     ->selectRaw('
        //         MIN(created_at) as first_date,
        //         SUM(total_amount) as total_revenue
        //     ')
        //     ->first();

        // $topUsers = WebsiteUser::withCount(['ecommerceOrders as delivered_orders_count' => function ($query) {
        //     $query->where('status', 'delivered');
        // }])
        //     ->orderByDesc('delivered_orders_count')
        //     ->take(3)
        //     ->get();

        // $topViewedProducts = EcommerceProduct::where('website_id', $this->website->id)
        //     ->orderByDesc('views_count')
        //     ->take(3)
        //     ->get();
        
        // $topSoldProducts = EcommerceProduct::where('website_id', $this->website->id)
        //     ->withCount(['orderItems as sales_count' => function($q) {
        //         $q->whereHas('order', function($q) {
        //             $q->where('status', 'delivered');
        //         });
        //     }])
        //     ->orderByDesc('sales_count')
        //     ->take(3)
        //     ->get();

        return Inertia::render('websites/restaurant/dashboard/Dashboard', [
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),

            // 'userStats' => [
            //     'all' => WebsiteUser::where('website_id', $this->website->id)->count(),
            //     'active' => $userStats['active'] ?? 0,
            //     'inactive' => $userStats['inactive'] ?? 0,
            //     'banned' => $userStats['banned'] ?? 0,
            // ],
            // 'productStats' => [
            //     'all' => EcommerceProduct::where('website_id', $this->website->id)->count(),
            //     'active' => $productStats[1] ?? 0,
            //     'inactive' => $productStats[0] ?? 0,
            // ],
            // 'orderStats' => [
            //     'pending' => $orderStats['pending'] ?? 0,
            //     'confirmed' => $orderStats['confirmed'] ?? 0,
            //     'delivered' => $orderStats['delivered'] ?? 0,
            //     'cancelled' => $orderStats['cancelled'] ?? 0,
            //     'rejected' => $orderStats['rejected'] ?? 0,
            //     'refunded' => $orderStats['refunded'] ?? 0,
            // ],
            // 'paymentStats' => [
            //     'totalRevenue' => $paymentStats['total_revenue'] ?? 0,
            //     'first_date' => $paymentStats['first_date'],
            // ],
            // 'topUsers' => $topUsers,
            // 'topViewedProducts' => $topViewedProducts,
            // 'topSoldProducts' => $topSoldProducts
        ]);
    }
}
