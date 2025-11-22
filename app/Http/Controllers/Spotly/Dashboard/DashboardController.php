<?php

namespace App\Http\Controllers\Spotly\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;

// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userStats = User::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $paymentStats = Payment::where('status', 'completed')
            ->selectRaw('
                MIN(created_at) as first_date,
                SUM(amount) as total_revenue
            ')
            ->first();

        $websiteStats = Website::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $websiteStatsActive = Website::selectRaw('is_active, COUNT(*) as count')
            ->groupBy('is_active')
            ->pluck('count', 'is_active');

        $websiteUserStats = WebsiteUser::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $websiteCountByType = Website::select('website_type_id')
            ->with(['websiteType:id,type'])
            ->selectRaw('count(*) as total')
            ->groupBy('website_type_id')
            ->get();

        $topUsers = User::withCount(['websites as approved_websites_count' => function ($query) {
            $query->where('status', 'approved');
        }])
            ->orderByDesc('approved_websites_count')
            ->take(3)
            ->get();


        $topWebsites = Website::withCount('websiteUsers')
            ->orderByDesc('website_users_count')
            ->take(3)
            ->get();

        return $this->inertiaRender('dashboard/Dashboard', [
            'userStats' => [
                'all' => User::count(),
                'active' => $userStats['active'] ?? 0,
                'inactive' => $userStats['inactive'] ?? 0,
                'banned' => $userStats['banned'] ?? 0,
            ],
            'paymentStats' => [
                'totalRevenue' => $paymentStats['total_revenue'] ?? 0,
                'first_date' => $paymentStats['first_date'],
            ],
            'websiteStats' => [
                'all' => Website::count(),
                'active' => $websiteStatsActive[1] ?? 0,
                'inactive' => $websiteStatsActive[0] ?? 0,
                'pending' => $websiteStats['pending'] ?? 0,
                'approved' => $websiteStats['approved'] ?? 0,
                'denied' => $websiteStats['denied'] ?? 0,
            ],
            'websiteUserStats' => [
                'all' => WebsiteUser::count(),
                'active' => $websiteUserStats['active'] ?? 0,
                'inactive' => $websiteUserStats['inactive'] ?? 0,
                'banned' => $websiteUserStats['banned'] ?? 0,
            ],
            'websiteCountByType' => $websiteCountByType,
            'topUsers' => $topUsers,
            'topWebsites' => $topWebsites
        ]);
    }
}
