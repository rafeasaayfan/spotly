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
            $countries = Country::with('media')->active()->get();

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
            $validatedLightLogo = $validated['light_logo'];
            $validatedDarkLogo = $validated['dark_logo'];
            unset($validated['light_logo'], $validated['dark_logo']);

            $oldEmail = $website->email;

            $website->fill($validated);
            if($oldEmail !== $validated['email']) {
                $website->email_verified_at = null;
            }
            $website->save();

            $website->updateMediaImage($validatedLightLogo, 'light_logo');
            $website->updateMediaImage($validatedDarkLogo, 'dark_logo');

            return $this->backSuccess(__('messages.website_updated'));
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
                return $this->backError(__('messages.website_not_approved'));
            }

            $website->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? __('messages.website_activated')
                : __('messages.website_deactivated');

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@activateWebsite', $e, 'An error occurred while updating the website status');
        }
    }
}
