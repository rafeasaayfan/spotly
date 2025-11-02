<?php

namespace App\Jobs\Websites\Ecommerce;

use App\Mail\Websites\ecommerce\OrderAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Websites\ecommerce\OrderClientMail;
use App\Models\WebsiteUser;

class SendOrderEmailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected string $orderNumber;
    protected string $action;
    protected int    $websiteId;
    protected string $websiteName;
    protected string $websiteEmail;
    protected string $websiteSubdomain;
    protected string | null $orderOwnerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $orderNumber,
        string $action,
        int $websiteId,
        string $websiteName,
        string $websiteEmail,
        string $websiteSubdomain,
        string | null $orderOwnerEmail
    ) {
        $this->orderNumber = $orderNumber;
        $this->action = $action;
        $this->websiteId = $websiteId;
        $this->websiteName = $websiteName;
        $this->websiteEmail = $websiteEmail;
        $this->websiteSubdomain = $websiteSubdomain;
        $this->orderOwnerEmail = $orderOwnerEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if($this->action === 'new_order') {
                $websiteUsers = WebsiteUser::where('website_id', $this->websiteId)->whereIn('role', ['owner', 'admin'])->get();

                foreach ($websiteUsers as $websiteUser) {
                    Mail::to($websiteUser->email)->send(new OrderAdminMail(
                        $this->orderNumber,
                        $this->websiteName,
                        $this->websiteEmail,
                        $this->websiteSubdomain
                    ));
                }
            }

            if ($this->orderOwnerEmail !== null) {
                Mail::to($this->orderOwnerEmail)->send(new OrderClientMail(
                    $this->orderNumber,
                    $this->action,
                    $this->websiteName,
                    $this->websiteEmail,
                    $this->websiteSubdomain,
                ));
            }

        } catch (\Exception $e) {
            Log::error('Failed to send order emails', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
