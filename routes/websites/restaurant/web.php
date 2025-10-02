<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::name('restaurant.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('websites/restaurant/templates/spotly-ui/pages/home/Home');
    })->name('home');

    Route::get('/shop', function () {
        return Inertia::render('websites/restaurant/templates/spotly-ui/pages/shop/Shop');
    })->name('shop');

    // Route::middleware(['auth:website', 'verified:website.verification.notice', 'websiteUserRole:admin'])->group(function () {
    //     require __DIR__ . '/dashboard.php';
    //     require __DIR__ . '/../common/dashboard.php';
    // });
});
