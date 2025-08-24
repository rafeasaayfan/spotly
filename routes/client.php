<?php

use App\Http\Controllers\Spotly\Client\ClientWebsitesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard/')->name('client.')->group(function () {
    Route::get('/my-websites', [ClientWebsitesController::class, 'index'])->name('myWebsites');

    Route::get('/my-website/{website}/edit', [ClientWebsitesController::class, 'edit'])->name('myWebsite.edit');

    Route::post('/my-website/{website}/update', [ClientWebsitesController::class, 'update'])->name('myWebsite.update');
    Route::patch('/my-website/{website}/activate', [ClientWebsitesController::class, 'activateWebsite'])->name('myWebsite.activate');

    Route::delete('/my-website/{website}/destroy', [ClientWebsitesController::class, 'destroy'])->name('myWebsite.destroy');
    Route::post('/my-website/sendOtp', [ClientWebsitesController::class, 'sendOtp'])->name('myWebsite.sendOtp');
});
