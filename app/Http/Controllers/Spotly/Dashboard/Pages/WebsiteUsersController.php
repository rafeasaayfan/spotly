<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\WebsiteUser;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\WebsiteUsers\StoreWebsiteUserRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteUsers\UpdateWebsiteUserRequest;
use App\Models\Country;

class WebsiteUsersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = WebsiteUser::query();

        $columnsSearching = ['website.name', 'name', 'email', 'phone_number'];
        $columnsSelection = [];
        $relations = ['website_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/websiteUsers/WebsiteUsers', ['websiteUsers' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);
            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return $this->jsonSuccess('', [
                'websites' => $websites,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteUsersController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $websiteUser = new WebsiteUser($validated);

            $websiteUser->save();

            return $this->redirectSuccess('dashboard.websiteUsers.index', 'Website User created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteUsersController@store', $e, 'An error occurred while creating the Website User');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = WebsiteUser::with('website')->findOrFail($id);
            $websiteUser = $this->flattenRelationData($query, ['website_name']);

            return $this->jsonSuccess('', [
                'data' => $websiteUser,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteUsersController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $websiteUser = WebsiteUser::findOrFail($id);
            $websites = $this->getRelation('website', ['name']);

            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return $this->jsonSuccess('', [
                'data' => $websiteUser,
                'websites' => $websites,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteUsersController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteUserRequest $request, WebsiteUser $websiteUser)
    {
        try {
            $validated = $request->validated();

            $websiteUser->fill($validated);
            $websiteUser->save();

            return $this->redirectSuccess('dashboard.websiteUsers.index', 'Website User updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteUsersController@update', $e, 'An error occurred while updating the Website User');
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
                'ids.*' => 'integer|exists:website_users,id',
            ]);

            WebsiteUser::destroy($validated['ids']);

            return $this->backSuccess('Website User(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteUsersController@destroy', $e, 'An error occurred while deleting the Website User(s)');
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $websiteUser = WebsiteUser::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:active,inactive,banned',
            ]);

            $websiteUser->update([
                'status' => $validated['status'],
            ]);

            $message = $validated['status'] === 'active'
                ? 'Website User marked as active.'
                : 'Website User marked as inactive.';

            if ($validated['status'] === 'banned') {
                $message = 'Website User marked as banned.';
            }

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteUsersController@changeStatus', $e, 'An error occurred while changing the website user status');
        }
    }
}
