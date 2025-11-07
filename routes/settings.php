<?php

use App\Http\Controllers\Spotly\Settings\PasswordController;
use App\Http\Controllers\Spotly\Settings\ProfileController;
use App\Http\Controllers\Spotly\Settings\DeleteAccountController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/delete-account', [DeleteAccountController::class, 'index'])->name('profile.destroy.index');
    Route::delete('settings/delete-account', [DeleteAccountController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/preferences', function () {
        return Inertia::render('settings/Preferences');
    })->name('appearance');
});
