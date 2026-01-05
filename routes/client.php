<?php

use App\Http\Controllers\Spotly\Client\Payments\MakePaymentController;
use App\Http\Controllers\Spotly\Client\Payments\PaymentsController;
use App\Http\Controllers\Spotly\Client\Websites\ClientWebsitesController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\DeleteWebsiteController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\EditWebsiteController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\UiWebsiteController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\ViewWebsiteController;
use App\Http\Controllers\Spotly\Client\DashboardController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\VerifyWebsiteEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard/')->name('client.')->group(function () {
    Route::get('/stats', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/my-websites', [ClientWebsitesController::class, 'index'])->name('myWebsites');

    // Edit
    Route::get('/my-website/{website}/edit', [EditWebsiteController::class, 'edit'])->name('myWebsite.edit');
    Route::post('/my-website/{website}/update', [EditWebsiteController::class, 'update'])->name('myWebsite.update');
    Route::patch('/my-website/{website}/activate', [EditWebsiteController::class, 'activateWebsite'])->name('myWebsite.activate');

    // Verify Email
    Route::post('/my-website/{website}/verify-email', [VerifyWebsiteEmailController::class, 'verify'])->name('myWebsite.verifyEmail');
    Route::post('/my-website/{website}/verify-email/send-otp', [VerifyWebsiteEmailController::class, 'sendOtp'])->name('myWebsite.verifyEmail.sendOtp');
    Route::post('/my-website/{website}/verify-email/check-last-otp', [VerifyWebsiteEmailController::class, 'checkLastOtp'])->name('myWebsite.verifyEmail.checkLastOtp');

    // Delete
    Route::delete('/my-website/{website}/destroy', [DeleteWebsiteController::class, 'destroy'])->name('myWebsite.destroy');
    Route::post('/my-website/{website}/destroy/send-otp', [DeleteWebsiteController::class, 'sendOtp'])->name('myWebsite.destroy.sendOtp');
    Route::post('/my-website/{website}/destroy/check-last-otp', [DeleteWebsiteController::class, 'checkLastOtp'])->name('myWebsite.destroy.checkLastOtp');

    // UI
    Route::get('/my-website/{website}/ui', [UiWebsiteController::class, 'index'])->name('myWebsite.ui');
    Route::patch('/my-website/{website}/ui/toggle-active', [UiWebsiteController::class, 'toggleActive'])->name('myWebsite.ui.toggleActive');
    Route::post('/my-website/{website}/ui/create', [UiWebsiteController::class, 'create'])->name('myWebsite.ui.create');
    Route::delete('/my-website/{website}/ui/destroy', [UiWebsiteController::class, 'destroy'])->name('myWebsite.ui.destroy');

    // View
    Route::get('/my-website/{website}/show', [ViewWebsiteController::class, 'index'])->name('myWebsite.show');

    // Make a Payment
    Route::get('/make-payment', [MakePaymentController::class, 'index'])->name('makePayment');
    Route::post('/make-payment/get-plans', [MakePaymentController::class, 'getPlans'])->name('makePayment.getPlans');
    // Payments
    Route::get('/my-payments', [PaymentsController::class, 'index'])->name('payments.index');
});
