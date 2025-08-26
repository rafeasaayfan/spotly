<?php

use App\Http\Controllers\Spotly\Client\Websites\ClientWebsitesController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\DeleteWebsiteController;
use App\Http\Controllers\Spotly\Client\Websites\Actions\EditWebsiteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard/')->name('client.')->group(function () {
    Route::get('/my-websites', [ClientWebsitesController::class, 'index'])->name('myWebsites');

    // Edit
    Route::get('/my-website/{website}/edit', [EditWebsiteController::class, 'edit'])->name('myWebsite.edit');
    Route::post('/my-website/{website}/update', [EditWebsiteController::class, 'update'])->name('myWebsite.update');
    Route::patch('/my-website/{website}/activate', [EditWebsiteController::class, 'activateWebsite'])->name('myWebsite.activate');

    // Delete
    Route::delete('/my-website/{website}/destroy', [DeleteWebsiteController::class, 'destroy'])->name('myWebsite.destroy');
    Route::post('/my-website/{website}/sendOtp', [DeleteWebsiteController::class, 'sendOtp'])->name('myWebsite.sendOtp');
    Route::post('/my-website/{website}/checkLastOtp', [DeleteWebsiteController::class, 'checkLastOtp'])->name('myWebsite.checkLastOtp');

    // UI
    Route::get('/my-website/{website}/ui', [EditWebsiteController::class, 'ui'])->name('myWebsite.ui');

    // View
    Route::get('/my-website/{website}/show', [EditWebsiteController::class, 'show'])->name('myWebsite.show');
});
