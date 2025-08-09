<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Websites\StoreWebsiteRequest;
use App\Http\Requests\Dashboard\Pages\Websites\UpdateWebsiteRequest;
use App\Models\Country;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebsitesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Website::query();

        $columnsSearching = ['owner.name', 'websiteType.type', 'name', 'subdomain', 'phone_number'];
        $columnsSelection = ['id', 'owner_id', 'website_type_id', 'approved_or_denied_by', 'subdomain', 'address', 'phone_number', 'is_active', 'is_verified', 'status'];
        $relations = ['owner_name', 'websiteType_type', 'approvedOrDeniedBy_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/websites/Websites', [
            'websites' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $users = $this->getRelation('user', ['name']);
            $websiteTypes = WebsiteType::select(['id', 'type'])->active()->get();
            $cities = config('cities.lebanon');
            $countries = Country::with('active')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return response()->json([
                'users' => $users,
                'websiteTypes' => $websiteTypes,
                'cities' => $cities,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on WebsitesController@create',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request)
    {
        try {
            $validated = $request->validated();
            unset($validated['logo_light'], $validated['logo_dark']);

            $website = new Website($validated);
            $website->country = 'Lebanon';

            if ($request->hasFile('logo_light') && $request->file('logo_light')->isValid()) {
                $website->addMediaFromRequest('logo_light')
                    ->toMediaCollection('logo_light');
            }

            if ($request->hasFile('logo_dark') && $request->file('logo_dark')->isValid()) {
                $website->addMediaFromRequest('logo_dark')
                    ->toMediaCollection('logo_dark');
            }

            $website->save();

            return redirect()->route('dashboard.websites.index')->with('message', 'Website created successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsitesController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the website. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $website = Website::with(['media', 'owner', 'websiteType', 'approvedOrDeniedBy'])->findOrFail($id);
            $website->logo_light = $website->getFirstMediaUrl('logo_light');
            $website->logo_dark = $website->getFirstMediaUrl('logo_dark');
            $result = $this->flattenRelationData($website, ['owner_name', 'websiteType_type', 'approvedOrDeniedBy_name']);

            return response()->json([
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on WebsitesController@show',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $website = Website::with('media')->findOrFail($id);
            $users = $this->getRelation('user', ['name']);
            $websiteTypes = $this->getRelation('websiteType', ['type'])
            ;
            $cities = config('cities.lebanon');
            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            $website->logo_light = $website->getFirstMediaUrl('logo_light');
            $website->logo_dark = $website->getFirstMediaUrl('logo_dark');

            return response()->json([
                'data' => $website,
                'users' => $users,
                'websiteTypes' => $websiteTypes,
                'cities' => $cities,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on WebsitesController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $website->clearMediaCollection('logo');
            $website->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        $website->save();

        return redirect()->route('dashboard.websites.index')->with('message', 'Website updated successfully');

        try {
            $validated = $request->validated();
            unset($validated['logo_light'], $validated['logo_dark']);

            $website->fill($validated);

            if ($request->hasFile('logo_light') && $request->file('logo_light')->isValid()) {
                $website->addMediaFromRequest('logo_light')
                    ->toMediaCollection('logo_light');
            }
            if ($request->hasFile('logo_dark') && $request->file('logo_dark')->isValid()) {
                $website->addMediaFromRequest('logo_dark')
                    ->toMediaCollection('logo_dark');
            }

            $website->save();

            return redirect()->route('dashboard.websites.index')->with('message', 'Website updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsitesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the website. Please try again.');
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
                'ids.*' => 'integer|exists:websites,id',
            ]);

            Website::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Website(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in WebsitesController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the website(s). Please try again.');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $website = Website::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $website->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Website activated successfully.'
                : 'Website deactivated successfully.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in WebsitesController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the website status. Please try again.');
        }
    }

    /**
     * Toggle verified status
     */
    public function toggleVerified(Request $request, $id)
    {
        try {
            $website = Website::findOrFail($id);

            $validated = $request->validate([
                'is_verified' => 'required|boolean',
            ]);

            $website->update([
                'is_verified' => $validated['is_verified'],
            ]);

            $message = $validated['is_verified']
                ? 'Website verified successfully.'
                : 'Website unverified successfully.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in WebsitesController@toggleVerified: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the website verification status. Please try again.');
        }
    }

    /**
     * Change status
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $website = Website::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:pending,denied,approved',
            ]);

            $website->update([
                'status' => $validated['status'],
                'approved_or_denied_by' => Auth::id(),
            ]);

            $message = $validated['status'] === 'approved'
                ? 'Website approved successfully.'
                : 'Website denied successfully.';

            if ($validated['status'] === 'pending') $message = 'The website is now pending.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in WebsitesController@changeStatus: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withErrors('An error occurred while changing the website status. Please try again.');
        }
    }
}
