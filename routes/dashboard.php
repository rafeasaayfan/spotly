<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Pages\Assignments\PermissionsController;
use App\Http\Controllers\Dashboard\Pages\Assignments\RolesController;
use App\Http\Controllers\Dashboard\Pages\RestaurantsController;
use App\Http\Controllers\Dashboard\Pages\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:dashboard_access', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Users
    dashboardPagesRoutes('users', UsersController::class);
    //* Restaurants
    dashboardPagesRoutes('restaurants', RestaurantsController::class);

    //* Assignments
    Route::prefix('assignments')->group(function () {
        dashboardPagesRoutes('permissions', PermissionsController::class);
        dashboardPagesRoutes('roles', RolesController::class);
    });
});

function dashboardPagesRoutes($name, $controller)
{
    Route::resource($name, $controller)->except(['destroy']);
    Route::post($name . '/destroy', [$controller, 'destroy'])->name($name . '.destroy');
}
