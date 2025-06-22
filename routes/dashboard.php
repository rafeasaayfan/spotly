<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Pages\Assignments\PermissionsController;
use App\Http\Controllers\Dashboard\Pages\Assignments\RolesController;
use App\Http\Controllers\Dashboard\Pages\Assignments\UserAssignmentsController;
use App\Http\Controllers\Dashboard\Pages\UsersController;
use App\Http\Controllers\Dashboard\Pages\WebsiteTypesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:dashboard_access', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Users
    dashboardPagesRoutes('users', UsersController::class);
    //* Website types
    dashboardPagesRoutes('websiteTypes', WebsiteTypesController::class);
    Route::patch('websiteTypes/{id}/is_active', [WebsiteTypesController::class, 'toggleActive'])->name('websiteTypes.is_active');

    //* Assignments
    Route::middleware('can:assignments_access')->prefix('assignments')->group(function () {
        // Permissions
        dashboardPagesRoutes('permissions', PermissionsController::class);
        Route::get('permissions/{id}/assign', [PermissionsController::class, 'assignment'])->name('permissions.assignRoles');
        Route::post('permissions/{id}/storeAssignments', [PermissionsController::class, 'storeAssignments'])->name('permissions.storeAssignments');

        // Roles
        dashboardPagesRoutes('roles', RolesController::class);
        Route::get('roles/{id}/assign', [RolesController::class, 'assignment'])->name('roles.assignPermissions');
        Route::post('roles/{id}/storeAssignments', [RolesController::class, 'storeAssignments'])->name('roles.storeAssignments');

        // Users assignments
        Route::prefix('usersAssignments')->group(function () {
            Route::get('/', [UserAssignmentsController::class, 'index'])->name('usersAssignments.index');

            Route::get('/{id}/assign', [UserAssignmentsController::class, 'assignment'])->name('usersAssignments.assignment');
            Route::post('/storeAssignments/{id}', [UserAssignmentsController::class, 'storeAssignments'])->name('usersAssignments.storeAssignments');
        });
    });
});

function dashboardPagesRoutes($name, $controller)
{
    Route::resource($name, $controller)->except(['destroy']);
    Route::post($name . '/destroy', [$controller, 'destroy'])->name($name . '.destroy');
}
