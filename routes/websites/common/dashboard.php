<?php

use App\Http\Controllers\Websites\Common\Dashboard\AttributesController;
use App\Http\Controllers\Websites\Common\Dashboard\BrandsController;
use App\Http\Controllers\Websites\Common\Dashboard\CategoriesController;
use App\Http\Controllers\Websites\Common\Dashboard\MessagesController;
use App\Http\Controllers\Websites\Common\Dashboard\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('websiteUserRole:owner')->group(function () {
        //* Users
        dashboardPagesRoutes('users', UsersController::class);
        Route::patch('users/{id}/is_active', [UsersController::class, 'toggleActive'])->name('brands.is_active');
        Route::patch('users/{id}/status', [UsersController::class, 'changeStatus'])->name('users.status');
        Route::patch('users/{id}/role', [UsersController::class, 'changeRole'])->name('users.role');
    });

    //* Categories
    dashboardPagesRoutes('categories', CategoriesController::class);
    Route::patch('categories/{id}/is_in_home', [CategoriesController::class, 'toggleIsInHome'])->name('categories.is_in_home');
    Route::patch('categories/{id}/is_active', [CategoriesController::class, 'toggleActive'])->name('categories.is_active');

    //* Brands
    dashboardPagesRoutes('brands', BrandsController::class);
    Route::patch('brands/{id}/is_active', [BrandsController::class, 'toggleActive'])->name('brands.is_active');

    //* Attributes
    dashboardPagesRoutes('attributes', AttributesController::class);
    Route::patch('attributes/{id}/is_active', [AttributesController::class, 'toggleActive'])->name('attributes.is_active');

    //* Messages
    dashboardPagesRoutes('messages', MessagesController::class);
    Route::patch('messages/{id}/status', [MessagesController::class, 'changeStatus'])->name('messages.status');
});
