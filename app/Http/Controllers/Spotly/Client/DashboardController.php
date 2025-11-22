<?php

namespace App\Http\Controllers\Spotly\Client;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteUser;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $userStats = WebsiteUser::whereHas('website', function ($q) use ($userId) {
            $q->where('owner_id', $userId);
        })->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $websiteStats = Website::where('owner_id', $userId)->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $websiteStatsActive = Website::where('owner_id', $userId)->selectRaw('is_active, COUNT(*) as count')
            ->groupBy('is_active')
            ->pluck('count', 'is_active');

        $websiteCountByType = Website::where('owner_id', $userId)->select('website_type_id')
            ->with(['websiteType:id,type'])
            ->selectRaw('count(*) as total')
            ->groupBy('website_type_id')
            ->get();

        $revenue = $this->getRevenueByOwner($userId);

        $topWebsites = Website::where('owner_id', $userId)->withCount('websiteUsers')
            ->orderByDesc('website_users_count')
            ->take(7)
            ->get();

        return $this->inertiaRender('client/Dashboard', [
            'userStats' => [
                'all' => WebsiteUser::whereHas('website', function ($q) use ($userId) {
                    $q->where('owner_id', $userId);
                })->count(),
                'active' => $userStats['active'] ?? 0,
                'inactive' => $userStats['inactive'] ?? 0,
                'banned' => $userStats['banned'] ?? 0,
            ],
            'websiteStats' => [
                'all' => Website::where('owner_id', $userId)->count(),
                'active' => $websiteStatsActive[1] ?? 0,
                'inactive' => $websiteStatsActive[0] ?? 0,
                'pending' => $websiteStats['pending'] ?? 0,
                'approved' => $websiteStats['approved'] ?? 0,
                'denied' => $websiteStats['denied'] ?? 0,
            ],
            'websiteCountByType' => $websiteCountByType,
            'revenue' => $revenue,
            'joinedAt' => Auth::user()->created_at,
            'topWebsites' => $topWebsites,
        ]);
    }

    /**
     * Calculate the total revenue and revenue breakdown by website type for a given owner.
     *
     * @param  int  $ownerId
     * @return array
     */
    protected function getRevenueByOwner($ownerId)
    {
        $websites = Website::with('websiteType')
            ->status('approved')
            ->where('owner_id', $ownerId)
            ->get();

        $totalRevenue = 0;
        $revenueByType = [];

        foreach ($websites as $website) {
            $type = $website->websiteType->type;

            switch ($type) {
                case 'e-commerce':
                    $revenue = $website->ecommerceOrders()
                        ->where('status', 'delivered')
                        ->sum('total_amount');
                    break;

                case 'restaurant':
                    $revenue = $website->restaurantOrders()
                        ->where('status', 'delivered')
                        ->sum('total_amount');
                    break;

                default:
                    $revenue = 0;
            }

            $totalRevenue += $revenue;
            $revenueByType[$type] = ($revenueByType[$type] ?? 0) + $revenue;
        }

        return [
            'total' => $totalRevenue,
            'by_type' => $revenueByType,
        ];
    }
}
