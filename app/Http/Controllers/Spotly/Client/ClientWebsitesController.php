<?php

namespace App\Http\Controllers\Spotly\Client;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteType;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\ClientWebsitesIndexRequest;
use App\Http\Requests\Client\UpdateWebsiteRequest;
use App\Models\Country;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
                ->orderBy(match ($sort_by) {
                    'newest' => 'created_at',
                    'oldest' => 'created_at',
                    'name_asc' => 'name',
                    'name_desc' => 'name',
                    default => 'created_at',
                }, match ($sort_by) {
                    'newest' => 'desc',
                    'oldest' => 'asc',
                    'name_asc' => 'asc',
                    'name_desc' => 'desc',
                    default => 'desc',
                })
                ->paginate($limit);

            $websiteTypes = WebsiteType::active()->select('id', 'title')->get();

            return $this->inertiaRender('client/myWebsites/Websites', [
                'websites' => $websites,
                'websiteTypes' => $websiteTypes
            ]);

        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@index', $e, 'An error occurred while fetching the websites');
        }
    }

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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Website $website, OtpService $otpService)
    {
        Gate::authorize('delete', $website);

        try {
            $validate = $request->validate([
                'code' => 'required|digits:6'
            ]);

            $user = User::findOrFail(Auth::id());

            if ($otpService->verify($user->id, 'delete_website', $validate['code'])) {
                $website->delete();

                return $this->redirectSuccess('client.myWebsites', 'Your Website deleted successfully');
            }

            return $this->redirectError('client.myWebsites', 'Invalid or expired OTP');

        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@destroy', $e, 'An error occurred while deleting the website');
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
                $this->backError('You cant Activate your website because it not approved from admins');
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

    /**
     * Send a new OTP CODE.
     */
    public function sendOtp(OtpService $otpService)
    {
        try {
            $user = User::findOrFail(Auth::id());

            $otp = $otpService->generate($user, 'delete_website');

            if (!$otp) {
                $this->jsonError('You have reached the maximum OTP requests for this hour');
            }

            $this->jsonSuccess('OTP sent successfully');
        } catch (\Exception $e) {
            $this->logJsonResponse('ClientWebsitesController@sendOtp', $e);
        }
    }
}
