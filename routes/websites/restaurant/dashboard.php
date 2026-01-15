<?php

// use App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages\DeliveryFeesController;

use App\Http\Controllers\Websites\Restaurant\Dashboard\DashboardController;
use App\Http\Controllers\Websites\Restaurant\Dashboard\Pages\CartsController;
use App\Http\Controllers\Websites\Restaurant\Dashboard\Pages\MenuItemsController;
use App\Http\Controllers\Websites\Restaurant\Dashboard\Pages\OrdersController;
use App\Http\Controllers\Websites\Restaurant\Dashboard\Pages\TrackOrdersController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Menu Items
    dashboardPagesRoutes('menu-items', MenuItemsController::class);
    Route::patch('menu-items/{id}/is_active', [MenuItemsController::class, 'toggleActive'])->name('menuItems.is_active');
    Route::patch('menu-items/{id}/is_in_home', [MenuItemsController::class, 'toggleIsInHome'])->name('menuItems.is_in_home');
    Route::patch('menu-items/{id}/is_special', [MenuItemsController::class, 'toggleIsSpecial'])->name('menuItems.is_special');
    Route::patch('menu-items/{id}/is_discount', [MenuItemsController::class, 'toggleIsDiscount'])->name('menuItems.is_discount');

    // //* Delivery Fees
    // // dashboardPagesRoutes('delivery-fees', DeliveryFeesController::class);

    //* Orders
    dashboardPagesRoutes('orders', OrdersController::class);
    Route::patch('orders/{id}/status', [OrdersController::class, 'changeStatus'])->name('orders.status');

    //* Track Orders
    dashboardPagesRoutes('track-orders', TrackOrdersController::class);

    //* Carts
    dashboardPagesRoutes('carts', CartsController::class);
    Route::patch('carts/{id}/status', [CartsController::class, 'changeStatus'])->name('carts.status');
});
