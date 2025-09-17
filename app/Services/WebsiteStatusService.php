<?php

namespace App\Services;

use App\Events\WebsiteApproved;
use App\Jobs\WebsiteStatusMailJob;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;

class WebsiteStatusService
{
    protected Website $website;

    /**
     * Create a new WebsiteStatusService instance.
     *
     * @param Website $website            
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Set the status of the website.
     *
     * Handles status transitions for the website:
     * - 'pending': Not allowed, returns an error.
     * - 'denied': Sets the website as inactive and updates status to denied.
     * - 'approved': Sets the website as active only if it has a valid subscription, otherwise inactive.
     *
     * @param string $status
     * @return array
     */
    public function setStatus(string $status): array
    {
        switch ($status) {
            case 'pending':
                return ['success' => false, 'message' => 'Cannot set website to pending'];

            case 'denied':
                $this->website->update([
                    'is_active' => false,
                    'status' => $status,
                    'approved_or_denied_by' => Auth::id(),
                ]);
                $this->sendMailStatus('status', false);
                return ['success' => true, 'message' => 'Website denied successfully'];

            case 'approved':
                $this->website->update([
                    'is_active' => $this->hasValidSubscription(),
                    'status' => $status,
                    'approved_or_denied_by' => Auth::id(),
                ]);
                event(new WebsiteApproved($this->website));
                $this->sendMailStatus('status', true);
                return ['success' => true, 'message' => 'Website approved successfully'];
        }

        return ['success' => false, 'message' => 'Unknown status'];
    }

    /**
     * Toggle the activation status of the website.
     *
     * If activating, ensures the website is approved and has a valid (paid) subscription.
     * Updates the 'is_active' field accordingly.
     *
     * @param bool $active
     * @return array
     */
    public function toggleActivation(bool $active): array
    {
        if ($active) {
            if ($this->website->status !== 'approved') {
                return ['success' => false, 'message' => 'This website is not approved yet'];
            }

            if (!$this->hasValidSubscription()) {
                return ['success' => false, 'message' => 'This website is not paid yet'];
            }
        }

        $this->website->update(['is_active' => $active]);
        $this->sendMailStatus('is_active', $active);

        return [
            'success' => true,
            'message' => $active ? 'Website activated successfully' : 'Website deactivated successfully',
        ];
    }

    /**
     * Check if the website has a valid (future) subscription.
     *
     * @return bool
     */
    private function hasValidSubscription(): bool
    {
        if (!$this->website->relationLoaded('subscription')) {
            $this->website->load(['subscription' => fn($q) => $q->select('id','website_id','end_date')]);
        }

        return $this->website->subscription?->end_date?->isFuture() ?? false;
    }

    /**
     * Dispatch a job to send a website status update email.
     *
     * @param string $type   The type of status being updated (e.g., 'is_active', 'is_verified', etc.)
     * @param bool   $success Whether the status update was successful (true or false)
     */
    private function sendMailStatus(string $type, bool $success)
    {
        WebsiteStatusMailJob::dispatch(
            $this->website->owner_id,
            $this->website->name,
            $this->website->subdomain,
            $type,
            $success
        );
    }
}
