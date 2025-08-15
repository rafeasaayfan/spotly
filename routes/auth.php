<?php

use App\Http\Controllers\Spotly\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Spotly\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Spotly\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Spotly\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Spotly\Auth\NewPasswordController;
use App\Http\Controllers\Spotly\Auth\PasswordResetLinkController;
use App\Http\Controllers\Spotly\Auth\RegisteredUserController;
use App\Http\Controllers\Spotly\Auth\SocialAccountsController;
use App\Http\Controllers\Spotly\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    //* ========================              ======================== *//
    //? =======================  Google Login ======================= *//
    //* ========================              ======================== *//
    Route::get('auth/google', [SocialAccountsController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('auth/google/callback', [SocialAccountsController::class, 'callbackFromGoogle']);
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
