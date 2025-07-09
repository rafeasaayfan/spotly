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
        $websites = $this->getRelation('website', ['name']);
        $countries = Country::active()->get();
        $countries->transform(function ($item) {
            $item->flag = $item->getFirstMediaUrl('flag');
            return $item;
        });

        return response()->json([
            'websites' => $websites,
            'countries' => $countries,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteUserRequest $request)
    {
        $validated = $request->validated();

        $websiteUser = new WebsiteUser($validated);

        $websiteUser->save();

        return redirect()->route('dashboard.websiteUsers.index')->with('message', 'Website User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = WebsiteUser::with('website')->findOrFail($id);
        $websiteUser = $this->flattenRelationData($query, ['website_name']);

        return response()->json([
            'data' => $websiteUser,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $websiteUser = WebsiteUser::findOrFail($id);
        $websites = $this->getRelation('website', ['name']);

        $countries = Country::active()->get();
        $countries->transform(function ($item) {
            $item->flag = $item->getFirstMediaUrl('flag');
            return $item;
        });

        return response()->json([
            'data' => $websiteUser,
            'websites' => $websites,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteUserRequest $request, WebsiteUser $websiteUser)
    {
        $validated = $request->validated();

        $websiteUser->fill($validated);

        $websiteUser->save();

        return redirect()->route('dashboard.websiteUsers.index')->with('message', 'Website User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:website_users,id',
        ]);

        WebsiteUser::destroy($validated['ids']);

        return redirect()->back()->with('message', __('WebsiteUser(s) deleted successfully.'));
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
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

        if ($validated['status'] === 'banned') $message = 'Website User marked as banned.';

        return redirect()->back()->with('message', $message);
    }
}
