<?php

namespace App\Services;

use App\Models\Otp;
use App\Jobs\OtpJob;
use App\Models\User;

class OtpService
{
    public function generate(User $user, $purpose, $codeLength = 6, $expiresMinutes = 3, $maxAttempts = 3)
    {
        $otp = Otp::firstOrCreate(
            ['user_id' => $user->id, 'purpose' => $purpose],
            ['attempts' => 0, 'code' => rand(100000, 999999), 'expires_at' => now()->addMinutes($expiresMinutes)]
        );

        // Just a 3 attempts in one hour
        if ($otp->updated_at->diffInHours(now()) >= 1) {
            $otp->attempts = 0;
        }

        if ($otp->attempts >= $maxAttempts) {
            return null;
        }

        if (!$otp->wasRecentlyCreated) {
            $otp->code = rand(pow(10, $codeLength-1), pow(10, $codeLength)-1);
            $otp->expires_at = now()->addMinutes($expiresMinutes);
            $otp->attempts += 1;
            $otp->save();
        }

        OtpJob::dispatch($user->email, $otp->code);

        return $otp;
    }

    public function verify($userId, $purpose, $code) : bool
    {
        $otp = Otp::where('user_id', $userId)->where('purpose', $purpose)->where('code', $code)->first();

        if (!$otp || $otp->isExpired()) {
            return false;
        }

        // $otp->delete();

        return true;
    }
}
