<?php

namespace App\Http\Controllers\Spotly\Client\Websites;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\ClientWebsitesIndexRequest;

class ClientWebsitesController extends Controller
{
    /**
     * Display a listing of the user's websites.
     */
    public function index(ClientWebsitesIndexRequest $request)
    {
        try {
            $cities = config('cities.lebanon');

            $websiteTypes = WebsiteType::active()->select('id', 'title')->get();
            $query = Website::where('owner_id', Auth::id())->with(['websiteType:id,type', 'subscription:website_id,status,start_date,end_date']);

            if (!empty($request->search)) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . trim($request->search) . '%')
                      ->orWhere('subdomain', 'like', '%' . trim($request->search) . '%');
                });
            }
            switch ($request->sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            };
            if ($request->website_type !== null) {
                $query->where('website_type_id', $request->website_type);
            }
            if (!empty($request->status)) {
                $query->where('status', $request->status);
            }
            if ($request->is_active !== null) {
                $query->where('is_active', (bool) $request->is_active);
            }

            $websites = $query->paginate($request->limit ? (int) $request->limit : 6);

            return $this->inertiaRender('client/myWebsites/Websites', [
                'websites' => $websites,
                'websiteTypes' => $websiteTypes,
                'cities' => $cities
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@index', $e, 'An error occurred while fetching the websites');
        }
    }
}
