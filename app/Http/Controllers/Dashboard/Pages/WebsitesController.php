<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Websites\StoreWebsiteRequest;
use App\Http\Requests\Dashboard\Pages\Websites\UpdateWebsiteRequest;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;

class WebsitesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Website::query();

        $columnsSearching = ['name', 'subdomain', 'phone_number'];
        $columnsSelection = ['id', 'owner_id', 'website_type_id', 'approved_or_denied_by', 'name', 'subdomain', 'address', 'phone_number', 'is_active', 'status', 'created_at'];
        $relations = ['owner_name', 'websiteType_type', 'viewedBy_name'];

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
        $users = $this->getRelation('user', ['name']);
        $websiteTypes = WebsiteType::select(['id', 'type'])->active()->get();

        return response()->json([
            'users' => $users,
            'websiteTypes' => $websiteTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request)
    {
        $validated = $request->validated();
        unset($validated['logo']);

        $website = new Website($validated);

        $website->country = 'Lebanon';
        $website->city = 'Beirut';

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $website->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        $website->save();

        return redirect()->route('dashboard.websites.index')->with('message', 'Website created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $website = Website::with(['owner', 'websiteType', 'viewedBy'])->findOrFail($id);
        $website->logo = $website->getFirstMediaUrl('logo');
        $result = $this->flattenRelationData($website, ['owner_name', 'websiteType_type', 'viewedBy_name']);


        return response()->json([
            'data' => $result,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $website = Website::findOrFail($id);
        $users = $this->getRelation('user', ['name']);
        $websiteTypes = $this->getRelation('websiteType', ['type']);

        $website->logo = $website->getFirstMediaUrl('logo');

        return response()->json([
            'data' => $website,
            'users' => $users,
            'websiteTypes' => $websiteTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        $validated = $request->validated();
        unset($validated['logo']);

        $website->fill($validated);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $website->clearMediaCollection('logo');
            $website->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        $website->save();

        return redirect()->route('dashboard.websites.index')->with('message', 'Website updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:websites,id',
        ]);

        Website::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Website(s) deleted successfully.'));
    }

    // Toggle active status
    public function toggleActive(Request $request, $id)
    {
        $websiteType = Website::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $websiteType->update([
            'is_active' => $validated['is_active'],
        ]);

        $message = $validated['is_active']
            ? 'Website activated successfully.'
            : 'Website deactivated successfully.';

        return redirect()->back()->with('message', $message);
    }

    // Change status
    public function changeStatus(Request $request, $id)
    {
        $websiteType = Website::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,denied,approved',
        ]);

        $websiteType->update([
            'status' => $validated['status'],
            'approved_or_denied_by' => Auth::id()
        ]);

        $message = $validated['status'] === 'approved'
            ? 'Website approved successfully.'
            : 'Website denied successfully.';

        if ($validated['status'] === 'pending') $message = 'The website is now pending.';

        return redirect()->back()->with('message', $message);
    }
}
