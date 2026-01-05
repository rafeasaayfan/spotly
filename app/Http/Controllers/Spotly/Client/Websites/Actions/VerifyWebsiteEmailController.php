<?php

namespace App\Http\Controllers\Spotly\Client\Websites\Actions;

use App\Enums\Spotly\OtpType;
use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use App\Services\Spotly\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VerifyWebsiteEmailController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function verify(Request $request, Website $website)
    {
        Gate::authorize('update', $website);

        if($website->email_verified_at || !$website->email) {
            return $this->backError('Website email verified or null.');
        }

        $validate = $request->validate([
            'code' => 'required|digits:6'
        ]);

        try {
            $otpService = new OtpService(
                Auth::user(),    
                $website->id,     
                $website->email,  
                OtpType::VERIFY_WEBSITE_EMAIL
            );
            if ($otpService->verify($validate['code'])) {
                $website->update([
                    'email_verified_at' => now(),
                ]);

                return $this->backSuccess(__('messages.website_email_verified'));
            }

            return $this->backError(__('messages.invalid_or_expired'));
        } catch (\Exception $e) {
            return $this->logResponse('VerifyWebsiteEmailController@verify', $e, 'An error occurred while verifying the website email');
        }
    }

    /**
     * Send a new OTP CODE.
     */
    public function sendOtp(Website $website)
    {
        Gate::authorize('update', $website);

        if($website->email_verified_at || !$website->email) {
            return $this->backError('Website email verified or null.');
        }

        try {

            $otpService = new OtpService(
                Auth::user(),    
                $website->id,      
                $website->email,  
                OtpType::VERIFY_WEBSITE_EMAIL
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
            return $this->logJsonResponse('VerifyWebsiteEmailController@sendOtp', $e);
        }
    }

    /**
     * Checking the last OTP CODE.
     */
    public function checkLastOtp(Website $website)
    {
        Gate::authorize('update', $website);

        if($website->email_verified_at || !$website->email) {
            return $this->jsonError('Website email verified or null.');
        }

        try {
            $otpService = new OtpService(
                Auth::user(),    
                $website->id,     
                $website->email,  
                OtpType::VERIFY_WEBSITE_EMAIL
            );
            $timer = $otpService->checkLastOtp($website);
            if ($timer) {
                return $this->jsonSuccess('', ['timer' => $timer]);
            }

            return $this->jsonSuccess('');
        } catch (\Exception $e) {
            return $this->logJsonResponse('VerifyWebsiteEmailController@checkLastOtp', $e);
        }
    }
}
