<?php

namespace App\Http\Controllers\Spotly\Client;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Client\ClientWebsitesIndexRequest;
use App\Http\Requests\Client\UpdateWebsiteRequest;
use App\Models\Country;
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $websiteId)
    { 
        $website = Website::with('media')->findOrFail($websiteId);
        $website->light_logo = $website->getFirstMediaUrl('light_logo');
        $website->dark_logo = $website->getFirstMediaUrl('dark_logo');

        $countries = Country::with('media')->active()->get();
        $countries->transform(function ($country) {
            $country->flag = $country->getFirstMediaUrl('flag');
            return $country;
        });

        $cities = config('cities.lebanon');

        return Inertia::render('client/myWebsites/actions/Edit', [
            'website' => $website,
            'countries' => $countries,
            'cities' => $cities,
        ]);
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

            return redirect()->back()->with('message', 'Website updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in ClientWebsitesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            dd($e->getTraceAsString());
            return redirect()->back()->withErrors('An error occurred while updating the website. Please try again.');
        }
    }
}
