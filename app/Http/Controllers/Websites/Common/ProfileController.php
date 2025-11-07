<?php

namespace App\Http\Controllers\Websites\Common;

use App\Http\Controllers\Websites\BaseController;
use App\Models\EcommerceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    /**
     * Show the user's profile page.
     */
    public function index()
    {
        $user = Auth::guard('website')->user();

        $baseQuery = EcommerceOrder::where('website_id', $this->website->id)->where('website_user_id', $user->id);

        // Order counts by status
        $pendingOrdersCount = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmedOrdersCount = (clone $baseQuery)->where('status', 'confirmed')->count();
        $deliveredOrdersCount = (clone $baseQuery)->where('status', 'delivered')->count();
        $failedOrdersCount = (clone $baseQuery)->whereIn('status', ['cancelled', 'refunded', 'rejected'])->count();
        
        // Total orders and spent
        $totalOrdersCount = (clone $baseQuery)->count();
        $totalSpent = (clone $baseQuery)->where('status', 'delivered')->sum('total_amount');
        
        return $this->inertiaRender('pages/Profile', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'created_at' => $user->created_at,
                'status' => $user->status,
                'role' => $user->role,
            ],
            'pendingOrdersCount' => $pendingOrdersCount,
            'confirmedOrdersCount' => $confirmedOrdersCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'failedOrdersCount' => $failedOrdersCount,
            'totalOrdersCount' => $totalOrdersCount,
            'totalSpent' => $totalSpent,

            'colors' => $this->websiteTemplate()->templateColor,
            'websiteNameAndLogo' => $this->websiteNameAndLogo(),
            'websiteFooterData' => $this->websiteFooterData(),
            'cartItemsCount' => $this->cartItems()?->count()
        ], true);
    }
}
