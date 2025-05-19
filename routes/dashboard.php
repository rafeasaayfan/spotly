<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Pages\ProjectsController;
use App\Http\Controllers\Dashboard\Pages\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:dashboard_access', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // dashboardPagesRoutes('users', UsersController::class);
    // dashboardPagesRoutes('users', ProjectsController::class);
});

function dashboardPagesRoutes($name, $controller) {
    Route::resource($name, $controller)->expect(['destroy']);
    Route::get($name . '/{id}/delete', [$controller, 'destroy'])->name($name . '.destroy');
}
