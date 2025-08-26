<?php

namespace App\Services;

use App\Models\Otp;
use App\Jobs\OtpJob;
use App\Models\User;

class OtpService
{
    protected User $user;
    protected int $websiteId;
    protected string $purpose;
    protected int $codeLength;
    protected int $expiresMinutes;
    protected int $maxAttempts;


    /**
     * Create a new OTP service instance.
     *
     * @param User   $user            The user who will receive the OTP.
     * @param int    $websiteId       The website ID to associate the OTP with.
     * @param string $purpose         The purpose (e.g., "login", "register", "reset").
     * @param int    $codeLength      The length of the OTP code (default: 6 digits).
     * @param int    $expiresMinutes  The validity duration of the OTP in minutes (default: 3).
     * @param int    $maxAttempts     The maximum number of OTP requests allowed within the limit (default: 3).
     */
    public function __construct(User $user, int $websiteId, string $purpose, int $codeLength = 6, int $expiresMinutes = 3, int $maxAttempts = 3)
    {
        $this->user = $user;
        $this->websiteId = $websiteId;
        $this->purpose = $purpose;
        $this->codeLength = $codeLength;
        $this->expiresMinutes = $expiresMinutes;
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * Send an OTP code to the user.
     *
     * - If a valid (non-expired) OTP already exists, return 'find'.
     * - Otherwise, generate a new OTP and dispatch a job to send it.
     *
     * @return bool|string
     */
    public function sendCode()
    {
        $lastOtp = Otp::where('user_id', $this->user->id)->where('website_id', $this->websiteId)->first();
        if ($lastOtp && !($lastOtp->isExpired())) {
            return 'find';
        }

        $otp = $this->generate();

        if (!$otp) {
            return false;
        }

        return true;
    }

    /**
     * Check the latest OTP for the user and return the seconds until expiration.
     *
     * - Returns null if no OTP exists or if the last OTP has expired.
     *
     * @return int|null
     */
    public function checkLastOtp()
    {
        $otp = Otp::where('user_id', $this->user->id)->where('website_id', $this->websiteId)->first();
        if (!$otp) {
            return null;
        }

        if ($otp->isExpired()) {
            return null;
        }

        return $otp->secondsUntilExpiration();
    }

    /**
     * Generate a new OTP or update the existing one.
     *
     * - Creates a new OTP if none exists for the given user/website/purpose.
     * - Resets attempts if more than 1 hour has passed since last update.
     * - Prevents generating if max attempts exceeded.
     * - If OTP exists, regenerates code, updates expiration, and increments attempts.
     * - Dispatches a job to send the OTP via email.
     *
     * @return Otp|null
     */
    public function generate()
    {
        $otp = Otp::firstOrCreate(
            ['user_id' => $this->user->id, 'website_id' => $this->websiteId, 'purpose' => $this->purpose],
            ['attempts' => 0, 'code' => rand(100000, 999999), 'expires_at' => now()->addMinutes($this->expiresMinutes)]
        );

        // Just a 3 attempts in one hour
        if ($otp->updated_at->diffInHours(now()) >= 1) {
            $otp->attempts = 0;
        }

        if ($otp->attempts >= $this->maxAttempts) {
            return null;
        }

        if (!$otp->wasRecentlyCreated) {
            $otp->code = rand(pow(10, $this->codeLength - 1), pow(10, $this->codeLength) - 1);
            $otp->expires_at = now()->addMinutes($this->expiresMinutes);
            $otp->attempts += 1;
            $otp->save();
        }

        OtpJob::dispatch($this->user->email, $otp->code);

        return $otp;
    }

    /**
     * Verify the provided OTP code.
     *
     * - Checks if OTP exists for the user/website/purpose and matches the code.
     * - Ensures the OTP is not expired.
     * - Returns true if valid, false otherwise.
     *
     * @param string $code
     * @return bool
     */
    public function verify(string $code): bool
    {
        $otp = Otp::where('user_id', $this->user->id)->where('website_id', $this->websiteId)->where('purpose', $this->purpose)->where('code', $code)->first();

        if (!$otp) {
            return false;
        }

        if ($otp->isExpired()) {
            return false;
        }

        // $otp->delete();

        return true;
    }
}
