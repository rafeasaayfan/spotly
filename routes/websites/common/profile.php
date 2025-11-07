<?php

use App\Http\Controllers\Websites\Common\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:website')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
});
