<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Pages\Assignments\PermissionsController;
use App\Http\Controllers\Dashboard\Pages\Assignments\RolesController;
use App\Http\Controllers\Dashboard\Pages\Assignments\UserAssignmentsController;
use App\Http\Controllers\Dashboard\Pages\CountriesController;
use App\Http\Controllers\Dashboard\Pages\EmailSubscribersController;
use App\Http\Controllers\Dashboard\Pages\MessagesController;
use App\Http\Controllers\Dashboard\Pages\BrandsController;
use App\Http\Controllers\Dashboard\Pages\UsersController;
use App\Http\Controllers\Dashboard\Pages\WebsiteMessagesController;
use App\Http\Controllers\Dashboard\Pages\WebsitesController;
use App\Http\Controllers\Dashboard\Pages\WebsiteTypesController;
use App\Http\Controllers\Dashboard\Pages\CategoriesController;
use App\Http\Controllers\Dashboard\Pages\PaymentMethodsController;
use App\Http\Controllers\Dashboard\Pages\Ui\TemplatesController;
use App\Http\Controllers\Dashboard\Pages\Ui\TemplateColorsController;
use App\Http\Controllers\Dashboard\Pages\Ui\TemplateTemplateColorsController;
use App\Http\Controllers\Dashboard\Pages\WebsiteUsersController;
use App\Http\Controllers\Dashboard\Pages\WebsitePaymentMethodsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::middleware(['auth', 'can:dashboard_access', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Users
    dashboardPagesRoutes('users', UsersController::class);
    Route::patch('users/{id}/status', [UsersController::class, 'changeStatus'])->name('users.status');
    //* Website types
    dashboardPagesRoutes('websiteTypes', WebsiteTypesController::class);
    Route::patch('websiteTypes/{id}/is_active', [WebsiteTypesController::class, 'toggleActive'])->name('websiteTypes.is_active');
    //* Email subscribers
    dashboardPagesRoutes('emailSubscribers', EmailSubscribersController::class);
    //* Websites
    dashboardPagesRoutes('websites', WebsitesController::class);
    Route::patch('websites/{id}/is_active', [WebsitesController::class, 'toggleActive'])->name('websites.is_active');
    Route::patch('websites/{id}/is_verified', [WebsitesController::class, 'toggleVerified'])->name('websites.is_verified');
    Route::patch('websites/{id}/status', [WebsitesController::class, 'changeStatus'])->name('websites.status');
    //* Website messages
    dashboardPagesRoutes('websiteMessages', WebsiteMessagesController::class);
    Route::patch('websiteMessages/{id}/status', [WebsiteMessagesController::class, 'changeStatus'])->name('websiteMessages.status');
    //* Messages
    dashboardPagesRoutes('messages', MessagesController::class);
    Route::patch('messages/{id}/status', [MessagesController::class, 'changeStatus'])->name('messages.status');
    //* Countries
    dashboardPagesRoutes('countries', CountriesController::class);
    Route::patch('countries/{id}/is_active', [CountriesController::class, 'toggleActive'])->name('countries.is_active');
    //* Brands
    dashboardPagesRoutes('brands', BrandsController::class);
    Route::patch('brands/{id}/is_active', [BrandsController::class, 'toggleActive'])->name('brands.is_active');
    //* Categories
    dashboardPagesRoutes('categories', CategoriesController::class);
    Route::patch('categories/{id}/is_active', [CategoriesController::class, 'toggleActive'])->name('categories.is_active');
    //* Payment Methods
    dashboardPagesRoutes('paymentMethods', PaymentMethodsController::class);
    Route::patch('paymentMethods/{id}/is_active', [PaymentMethodsController::class, 'toggleActive'])->name('paymentMethods.is_active');
    //* Website Payment Methods
    dashboardPagesRoutes('websitePaymentMethods', WebsitePaymentMethodsController::class);
    Route::patch('websitePaymentMethods/{id}/is_active', [WebsitePaymentMethodsController::class, 'toggleActive'])->name('websitePaymentMethods.is_active');
    //* Website Users
    dashboardPagesRoutes('websiteUsers', WebsiteUsersController::class);
    Route::patch('websiteUsers/{id}/status', [WebsiteUsersController::class, 'changeStatus'])->name('websiteUsers.status');

    //* ui
    Route::prefix('ui')->group(function () {
        //* Templates
        dashboardPagesRoutes('templates', TemplatesController::class);
        Route::patch('templates/{id}/is_active', [TemplatesController::class, 'toggleActive'])->name('templates.is_active');

        //* Template Colors
        dashboardPagesRoutes('templateColors', TemplateColorsController::class);
        Route::patch('templateColors/{id}/is_active', [TemplateColorsController::class, 'toggleActive'])->name('templateColors.is_active');

        //* Template Template Colors
        dashboardPagesRoutes('templateTemplateColors', TemplateTemplateColorsController::class);
        Route::patch('templateTemplateColors/{id}/is_default', [TemplateTemplateColorsController::class, 'toggleDefault'])->name('templateTemplateColors.is_default');
    });


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
    $param = Str::singular($name);

    Route::resource($name, $controller)->except(['destroy', 'update']);
    Route::post($name . '/{' . $param . '}/update', [$controller, 'update'])->name($name . '.update');
    Route::post($name . '/destroy', [$controller, 'destroy'])->name($name . '.destroy');
}
