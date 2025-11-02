<?php

namespace App\Http\Controllers\Spotly\Client\Websites\Actions;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeleteWebsiteController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Website $website)
    {
        Gate::authorize('delete', $website);

        $validate = $request->validate([
            'code' => 'required|digits:6'
        ]);

        try {
            $otpService = new OtpService(
                Auth::user(),    
                $website->id,        
                'delete_website'     
            );
            if ($otpService->verify($validate['code'])) {
                $website->delete();

                return $this->redirectSuccess('client.myWebsites', __('messages.website_deleted'));
            }

            return redirect()->back()->withErrors([
                'toastType' => 'error',
                'message' => __('messages.invalid_or_expired')
            ]);
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@destroy', $e, 'An error occurred while deleting the website');
        }
    }

    /**
     * Send a new OTP CODE.
     */
    public function sendOtp(Website $website)
    {
        Gate::authorize('delete', $website);

        try {

            $otpService = new OtpService(
                Auth::user(),    
                $website->id,        
                'delete_website'     
            );
            $result = $otpService->sendCode();

            if($result) {
                return $this->jsonSuccess(__('messages.otp_sent'));
            } else if(!$result) {
                return $this->jsonError(__('messages.otp_rate_limited'));
            } else {
                return $this->jsonError(__('messages.active_code_exists'));
            }
        } catch (\Exception $e) {
            return $this->logJsonResponse('ClientWebsitesController@sendOtp', $e);
        }
    }

    /**
     * Checking the last OTP CODE.
     */
    public function checkLastOtp(Website $website)
    {
        Gate::authorize('delete', $website);

        try {
            $otpService = new OtpService(
                Auth::user(),    
                $website->id,        
                'delete_website'     
            );
            $timer = $otpService->checkLastOtp($website);
            if ($timer) {
                return $this->jsonSuccess('', ['timer' => $timer]);
            }

            return $this->jsonSuccess('');
        } catch (\Exception $e) {
            return $this->logJsonResponse('ClientWebsitesController@sendOtp', $e);
        }
    }
}
