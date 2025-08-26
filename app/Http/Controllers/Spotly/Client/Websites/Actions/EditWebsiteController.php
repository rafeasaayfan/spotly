<?php

namespace App\Http\Controllers\Spotly\Client\Websites\Actions;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Http\Requests\Client\UpdateWebsiteRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EditWebsiteController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        Gate::authorize('view', $website);

        try {
            $website->light_logo = $website->getFirstMediaUrl('light_logo');
            $website->dark_logo = $website->getFirstMediaUrl('dark_logo');

            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($country) {
                $country->flag = $country->getFirstMediaUrl('flag');
                return $country;
            });

            $cities = config('cities.lebanon');

            return $this->inertiaRender('client/myWebsites/actions/Edit', [
                'website' => $website,
                'countries' => $countries,
                'cities' => $cities,
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@edit', $e, 'An error occurred while showing the edit resource');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website)
    {
        Gate::authorize('update', $website);

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

            return $this->backSuccess('Website updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@update', $e, 'An error occurred while updating the website');
        }
    }

    /**
     * Update the website activation.
     */
    public function activateWebsite(Request $request, Website $website)
    {
        Gate::authorize('activate', $website);

        try {
            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            if ($website->status !== 'approved') {
                return $this->backError('You cant Activate your website because it not approved from admins');
            }

            $website->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Your Website activated successfully.'
                : 'Your Website deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@activateWebsite', $e, 'An error occurred while updating the website status');
        }
    }
}
