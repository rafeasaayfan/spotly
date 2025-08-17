<?php

use App\Http\Controllers\Spotly\Dashboard\DashboardController;
use App\Http\Controllers\Spotly\Dashboard\Pages\Assignments\PermissionsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\Assignments\RolesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\Assignments\UserAssignmentsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\CountriesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\EmailSubscribersController;
use App\Http\Controllers\Spotly\Dashboard\Pages\MessagesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\BrandsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\UsersController;
use App\Http\Controllers\Spotly\Dashboard\Pages\WebsiteMessagesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\WebsitesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\WebsiteTypesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\CategoriesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\PaymentMethodsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\PlansController;
use App\Http\Controllers\Spotly\Dashboard\Pages\Ui\TemplatesController;
use App\Http\Controllers\Spotly\Dashboard\Pages\Ui\TemplateColorsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\Ui\TemplateTemplateColorsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\WebsiteUsersController;
use App\Http\Controllers\Spotly\Dashboard\Pages\WebsitePaymentMethodsController;
use App\Http\Controllers\Spotly\Dashboard\Pages\WebsiteTemplatesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'can:dashboard_access'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //* Users
    dashboardPagesRoutes('users', UsersController::class);
    Route::patch('users/{id}/status', [UsersController::class, 'changeStatus'])->name('users.status');
    //* Website types
    dashboardPagesRoutes('website-types', WebsiteTypesController::class);
    Route::patch('website-types/{id}/is_active', [WebsiteTypesController::class, 'toggleActive'])->name('websiteTypes.is_active');
    //* Email subscribers
    dashboardPagesRoutes('email-subscribers', EmailSubscribersController::class);
    //* Websites
    dashboardPagesRoutes('websites', WebsitesController::class);
    Route::patch('websites/{id}/is_active', [WebsitesController::class, 'toggleActive'])->name('websites.is_active');
    Route::patch('websites/{id}/is_verified', [WebsitesController::class, 'toggleVerified'])->name('websites.is_verified');
    Route::patch('websites/{id}/status', [WebsitesController::class, 'changeStatus'])->name('websites.status');
    //* Website messages
    dashboardPagesRoutes('website-messages', WebsiteMessagesController::class);
    Route::patch('website-messages/{id}/status', [WebsiteMessagesController::class, 'changeStatus'])->name('websiteMessages.status');
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
    dashboardPagesRoutes('payment-methods', PaymentMethodsController::class);
    Route::patch('payment-methods/{id}/is_active', [PaymentMethodsController::class, 'toggleActive'])->name('paymentMethods.is_active');
    //* Website Payment Methods
    dashboardPagesRoutes('website-payment-methods', WebsitePaymentMethodsController::class);
    Route::patch('website-payment-methods/{id}/is_active', [WebsitePaymentMethodsController::class, 'toggleActive'])->name('websitePaymentMethods.is_active');
    //* Website Users
    dashboardPagesRoutes('website-users', WebsiteUsersController::class);
    Route::patch('website-users/{id}/status', [WebsiteUsersController::class, 'changeStatus'])->name('websiteUsers.status');
    //* Website Templates
    dashboardPagesRoutes('website-templates', WebsiteTemplatesController::class);
    Route::patch('website-templates/{id}/is_active', [WebsiteTemplatesController::class, 'toggleActive'])->name('websiteTemplates.is_active');
    //* Plans
    dashboardPagesRoutes('plans', PlansController::class);
    Route::patch('plans/{id}/is_active', [PlansController::class, 'toggleActive'])->name('plans.is_active');

    //* ui
    Route::prefix('ui')->group(function () {
        //* Templates
        dashboardPagesRoutes('templates', TemplatesController::class);
        Route::patch('templates/{id}/is_active', [TemplatesController::class, 'toggleActive'])->name('templates.is_active');

        //* Template Colors
        dashboardPagesRoutes('template-colors', TemplateColorsController::class);
        Route::patch('template-colors/{id}/is_active', [TemplateColorsController::class, 'toggleActive'])->name('templateColors.is_active');

        //* Template Template Colors
        dashboardPagesRoutes('template-template-colors', TemplateTemplateColorsController::class);
        Route::patch('template-template-colors/{id}/is_default', [TemplateTemplateColorsController::class, 'toggleDefault'])->name('templateTemplateColors.is_default');
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
        Route::prefix('user-assignments')->name('userAssignments.')->group(function () {
            Route::get('/', [UserAssignmentsController::class, 'index'])->name('index');

            Route::get('/{id}/assign', [UserAssignmentsController::class, 'assignment'])->name('assignment');
            Route::post('/storeAssignments/{id}', [UserAssignmentsController::class, 'storeAssignments'])->name('storeAssignments');
        });
    });
});