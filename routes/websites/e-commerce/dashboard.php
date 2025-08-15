<?php

use App\Http\Controllers\Websites\ECommerce\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('e-commerce.dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});