<?php

namespace App\Console\Commands;

use App\Jobs\Spotly\ExpiredSoonSubscriptionMailJob;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSoonSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiring-soon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring soon subscriptions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting expiring soon subscriptions check...');

        try {
            $expiringSoonSubscriptions = Subscription::where('status', 'active')
                ->expiringSoon()->with(['website:id,owner_id,name,email', 'website.owner:id,email,name'])
                ->select('id', 'website_id', 'end_date')->get();

            if($expiringSoonSubscriptions->isEmpty()) {
                Log::info('No expiring soon subscriptions found');
                return Command::SUCCESS;
            }

            ExpiredSoonSubscriptionMailJob::dispatch($expiringSoonSubscriptions);

            Log::info('Expiring soon subscriptions check completed', [
                'expiring_soon_subscriptions' => $expiringSoonSubscriptions->count(),
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("An error occurred while checking expiring soon subscriptions: {$e->getMessage()}");

            Log::error('Failed to check expiring soon subscriptions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
