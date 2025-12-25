<?php

use App\Http\Controllers\Websites\Ecommerce\Dashboard\DashboardController;
use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\CartsController;
// use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\DeliveryFeesController;
use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\OrdersController;
use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\ProductsController;
use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\TrackOrdersController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Products
    dashboardPagesRoutes('products', ProductsController::class);
    Route::patch('products/{id}/is_active', [ProductsController::class, 'toggleActive'])->name('products.is_active');
    Route::patch('products/{id}/is_in_home', [ProductsController::class, 'toggleIsInHome'])->name('products.is_in_home');
    Route::patch('products/{id}/is_special', [ProductsController::class, 'toggleIsSpecial'])->name('products.is_special');
    Route::patch('products/{id}/is_discount', [ProductsController::class, 'toggleIsDiscount'])->name('products.is_discount');

    //* Delivery Fees
    // dashboardPagesRoutes('delivery-fees', DeliveryFeesController::class);

    //* Orders
    dashboardPagesRoutes('orders', OrdersController::class);
    Route::patch('orders/{id}/status', [OrdersController::class, 'changeStatus'])->name('orders.status');

    //* Track Orders
    dashboardPagesRoutes('track-orders', TrackOrdersController::class);

    //* Carts
    dashboardPagesRoutes('carts', CartsController::class);
    Route::patch('carts/{id}/status', [CartsController::class, 'changeStatus'])->name('carts.status');
});
