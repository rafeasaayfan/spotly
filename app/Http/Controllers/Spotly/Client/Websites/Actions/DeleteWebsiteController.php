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

                return $this->redirectSuccess('client.myWebsites', 'Your Website deleted successfully');
            }

            return redirect()->back()->withErrors([
                'toastType' => 'error',
                'message' => 'Invalid or expired OTP'
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
                return $this->jsonSuccess('OTP sent successfully');
            } else if(!$result) {
                return $this->jsonError('You have reached the maximum OTP requests for this hour');
            } else {
                return $this->jsonError('You already have a active code');
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
