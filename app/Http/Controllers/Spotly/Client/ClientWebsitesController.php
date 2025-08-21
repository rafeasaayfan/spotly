<?php

namespace App\Http\Controllers\Spotly\Client;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Client\ClientWebsitesIndexRequest;
use Inertia\Inertia;

class ClientWebsitesController extends Controller
{
    /**
     * Display a listing of the user's websites.
     */
    public function index(ClientWebsitesIndexRequest $request)
    { 
        $search = trim($request->input('search', ''));
        $sort_by = $request->input('sort_by', 'newest');
        $website_type_id = $request->input('website_type', '');
        $status = $request->input('status', '');
        $is_active = $request->input('active', '');
        $limit = (int)$request->input('limit', 6);

        try {
            $websites = Website::where('owner_id', Auth::id())
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('subdomain', 'like', '%' . $search . '%');
                })
                ->when($website_type_id, function ($query, $website_type_id) {
                    $query->where('website_type_id', $website_type_id);
                })
                ->when($status, function ($query, $status) {
                    $query->where('status', $status);
                })
                ->when($is_active !== '', function ($query) use ($is_active) {
                    $query->where('is_active', (bool) $is_active);
                })
                ->with([
                    'media',
                    'websiteType:id,type',
                    'websiteActiveTemplateColor.template:id,name',
                    'websiteActiveTemplateColor.templateColor:id,name',
                ])
                ->orderBy(match($sort_by) {
                    'newest' => 'created_at',
                    'oldest' => 'created_at',
                    'name_asc' => 'name',
                    'name_desc' => 'name',
                    default => 'created_at',
                }, match($sort_by) {
                    'newest' => 'desc',
                    'oldest' => 'asc',
                    'name_asc' => 'asc',
                    'name_desc' => 'desc',
                    default => 'desc',
                })
                ->paginate($limit);

            $websites->transform(function ($item) {
                $item->lightLogo = $item->getFirstMediaUrl('lightLogo');
                $item->darktLogo = $item->getFirstMediaUrl('darktLogo');
                return $item;
            });

            $websiteTypes = WebsiteType::active()->select('id', 'title')->get();

            return Inertia::render('client/myWebsites/Websites', [
                'websites' => $websites,
                'websiteTypes' => $websiteTypes
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ClientWebsitesController@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while fetching the websites. Please try again.');
        }
    }
}
