<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebsiteUser;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\WebsiteUsers\StoreWebsiteUserRequest;
use App\Http\Requests\Dashboard\Pages\WebsiteUsers\UpdateWebsiteUserRequest;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class WebsiteUsersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebsiteUser::query();

        $columnsSearching = ['website.name', 'name', 'email', 'phone_number'];
        $columnsSelection = [];
        $relations = ['website_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/websiteUsers/WebsiteUsers', [
            'websiteUsers' => $data,
        ]);
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

            return response()->json([
                'websites' => $websites,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error loading create form data.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
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

            return redirect()->route('dashboard.websiteUsers.index')->with('message', 'Website User created successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsiteUsersController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the Website User. Please try again.');
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

            return response()->json([
                'data' => $websiteUser,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching Website User details.',
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
            $websiteUser = WebsiteUser::findOrFail($id);
            $websites = $this->getRelation('website', ['name']);

            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return response()->json([
                'data' => $websiteUser,
                'websites' => $websites,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error loading Website User for edit.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
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

            return redirect()->route('dashboard.websiteUsers.index')->with('message', 'Website User updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsiteUsersController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the Website User. Please try again.');
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

            return redirect()->back()->with('message', __('WebsiteUser(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in WebsiteUsersController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting Website User(s). Please try again.');
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

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in WebsiteUsersController@changeStatus: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while changing status. Please try again.');
        }
    }
}
