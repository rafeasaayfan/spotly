<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Website;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Websites\StoreWebsiteRequest;
use App\Http\Requests\Dashboard\Pages\Websites\UpdateWebsiteRequest;
use App\Jobs\WebsiteStatusMailJob;
use App\Models\Country;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;

class WebsitesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Website::query();

        $columnsSearching = ['owner.name', 'websiteType.type', 'name', 'subdomain', 'phone_number'];
        $columnsSelection = ['id', 'owner_id', 'website_type_id', 'approved_or_denied_by', 'subdomain', 'address', 'phone_number', 'is_active', 'is_verified', 'status'];
        $relations = ['owner_name', 'websiteType_type', 'approvedOrDeniedBy_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/websites/Websites', ['websites' => $data]);
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
            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return $this->jsonSuccess('', [
                'users' => $users,
                'websiteTypes' => $websiteTypes,
                'cities' => $cities,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsitesController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request)
    {
        try {
            $validated = $request->validated();
            unset($validated['light_logo'], $validated['dark_logo']);

            $website = new Website($validated);
            $website->country = 'Lebanon';

            if ($request->hasFile('light_logo') && $request->file('light_logo')->isValid()) {
                $website->addMediaFromRequest('light_logo')
                    ->toMediaCollection('light_logo');
            }

            if ($request->hasFile('dark_logo') && $request->file('dark_logo')->isValid()) {
                $website->addMediaFromRequest('dark_logo')
                    ->toMediaCollection('dark_logo');
            }

            $website->save();

            return $this->redirectSuccess('dashboard.websites.index', 'Website created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsitesController@store', $e, 'An error occurred while creating the website');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $website = Website::with(['media', 'owner', 'websiteType', 'approvedOrDeniedBy'])->findOrFail($id);
            $website->light_logo = $website->getFirstMediaUrl('light_logo');
            $website->dark_logo = $website->getFirstMediaUrl('dark_logo');
            $result = $this->flattenRelationData($website, ['owner_name', 'websiteType_type', 'approvedOrDeniedBy_name']);

            return $this->jsonSuccess('', [
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsitesController@show', $e, 'An error when fetching the show page');
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
            $websiteTypes = $this->getRelation('websiteType', ['type']);
            $cities = config('cities.lebanon');
            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            $website->light_logo = $website->getFirstMediaUrl('light_logo');
            $website->dark_logo = $website->getFirstMediaUrl('dark_logo');

            return $this->jsonSuccess('', [
                'data' => $website,
                'users' => $users,
                'websiteTypes' => $websiteTypes,
                'cities' => $cities,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsitesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        try {
            $validated = $request->validated();
            unset($validated['light_logo'], $validated['dark_logo']);

            $website->fill($validated);

            if ($request->hasFile('light_logo') && $request->file('light_logo')->isValid()) {
                $website->clearMediaCollection('light_logo');
                $website->addMediaFromRequest('light_logo')
                    ->toMediaCollection('light_logo');
            }
            if ($request->hasFile('dark_logo') && $request->file('dark_logo')->isValid()) {
                $website->clearMediaCollection('dark_logo');
                $website->addMediaFromRequest('dark_logo')
                    ->toMediaCollection('dark_logo');
            }

            $website->save();

            return $this->redirectSuccess('dashboard.websites.index', 'Website updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsitesController@update', $e, 'An error occurred while updating the website');
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

            return $this->backSuccess('Website(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsitesController@destroy', $e, 'An error occurred while deleting the website(s)');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $website = Website::findOrFail($id);

            if ($website->status !== 'approved') {
                return $this->backError('This website is not approved yet');
            }

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $website->update([
                'is_active' => $validated['is_active'],
            ]);

            WebsiteStatusMailJob::dispatch(
                $website->owner_id,
                $website->name,
                $website->subdomain,
                'is_active',
                $validated['is_active'] ? 1 : 0
            );

            $message = $validated['is_active']
                ? 'Website activated successfully.'
                : 'Website deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsitesController@toggleActive', $e, 'An error occurred while updating the website status');
        }
    }

    /**
     * Toggle verified status
     */
    public function toggleVerified(Request $request, $id)
    {
        try {
            $website = Website::findOrFail($id);

            if ($website->status !== 'approved') {
                return $this->backError('This website is not approved yet');
            }

            $validated = $request->validate([
                'is_verified' => 'required|boolean',
            ]);

            $website->update([
                'is_verified' => $validated['is_verified'],
            ]);

            WebsiteStatusMailJob::dispatch(
                $website->owner_id,
                $website->name,
                $website->subdomain,
                'is_verified',
                $validated['is_verified'] ? 1 : 0
            );

            $message = $validated['is_verified']
                ? 'Website verified successfully.'
                : 'Website unverified successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsitesController@toggleVerified', $e, 'An error occurred while updating the website verification status');
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

            if ($validated['status'] === 'pending') {
                return $this->backError('Cant make the website pending', 'warning');
            }

            $website->update([
                'status' => $validated['status'],
                'approved_or_denied_by' => Auth::id(),
            ]);

            WebsiteStatusMailJob::dispatch(
                $website->owner_id,
                $website->name,
                $website->subdomain,
                'status',
                $validated['status'] === 'approved' ? 1 : 0
            );

            $message = $validated['status'] === 'approved'
                ? 'Website approved successfully.'
                : 'Website denied successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsitesController@changeStatus', $e, 'An error occurred while changing the website status');
        }
    }
}
