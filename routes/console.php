<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the expired subscriptions check to run daily at midnight
Schedule::command('subscriptions:check-expired')
    ->twiceDaily(7, 22)
    ->withoutOverlapping()
    ->onFailure(function () {
        Log::error('Scheduled command subscriptions:check-expired failed');
    });

Schedule::command('subscriptions:check-expiring-soon')
    ->dailyAt('07:30')
    ->withoutOverlapping()
    ->onFailure(function () {
        Log::error('Scheduled command subscriptions:check-expiring-soon failed');
    });