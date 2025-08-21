<?php

use App\Http\Controllers\Spotly\Client\ClientWebsitesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard/')->name('client.')->group(function () {
    Route::get('/my-websites', [ClientWebsitesController::class, 'index'])->name('myWebsites');
    Route::get('/my-website/{id}/edit', [ClientWebsitesController::class, 'edit'])->name('myWebsite.edit');
    Route::post('/my-website/{website}/update', [ClientWebsitesController::class, 'update'])->name('myWebsite.update');
});
