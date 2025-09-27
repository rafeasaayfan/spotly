<?php

use App\Http\Controllers\Websites\Ecommerce\Dashboard\DashboardController;
use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\ProductsController;
use Illuminate\Support\Facades\Route;

// middleware('websiteRole:admin')->
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Products
    dashboardPagesRoutes('products', ProductsController::class);
    Route::patch('products/{id}/is_active', [ProductsController::class, 'toggleActive'])->name('products.is_active');
    Route::patch('products/{id}/is_in_home', [ProductsController::class, 'toggleIsInHome'])->name('products.is_in_home');
    Route::patch('products/{id}/is_special', [ProductsController::class, 'toggleIsSpecial'])->name('products.is_special');
});