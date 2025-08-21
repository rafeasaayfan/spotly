<?php

use App\Http\Controllers\Spotly\Client\ClientWebsitesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('client.')->group(function () {
    Route::get('/my-websites', [ClientWebsitesController::class, 'index'])->name('myWebsites');
});
