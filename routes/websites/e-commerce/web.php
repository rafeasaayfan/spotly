<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::name('e-commerce.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('websites/e-commerce/templates/spotly-ui/pages/home/Home');
    })->name('home');

    Route::get('/shop', function () {
        return Inertia::render('websites/e-commerce/templates/spotly-ui/pages/shop/Shop');
    })->name('shop');

    Route::middleware(['auth:website', 'verified:website.verification.notice'])->group(function () {
        require __DIR__ . '/dashboard.php';
    });
});
