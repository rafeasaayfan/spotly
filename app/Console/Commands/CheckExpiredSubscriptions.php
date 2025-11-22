<?php

namespace App\Console\Commands;

use App\Jobs\Spotly\ExpiredSubscriptionMailJob;
use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and deactivate associated websites';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting expired subscriptions check...');

        try {
            DB::beginTransaction();

            $expiredSubscriptionIds = Subscription::where('status', 'active')
                ->expired()
                ->pluck('id');

            if ($expiredSubscriptionIds->isEmpty()) {
                Log::info('No expired subscriptions found');
                DB::commit();
                return Command::SUCCESS;
            }

            Subscription::whereIn('id', $expiredSubscriptionIds)
                ->update(['status' => 'expired']);

            Website::whereHas('subscription', function ($q) use ($expiredSubscriptionIds) {
                $q->whereIn('id', $expiredSubscriptionIds);
            })
                ->where('is_active', true)
                ->update(['is_active' => false]);

            DB::commit();

            ExpiredSubscriptionMailJob::dispatch($expiredSubscriptionIds)->afterCommit();

            Log::info('Expired subscriptions check completed', [
                'expired_subscriptions' => $expiredSubscriptionIds->count(),
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error("An error occurred while checking expired subscriptions: {$e->getMessage()}");

            Log::error('Failed to check expired subscriptions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
