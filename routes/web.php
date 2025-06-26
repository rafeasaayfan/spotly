<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\WebsiteBuilderController;
use App\Http\Middleware\HandleLanguage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/setLang/{lang}', function ($lang = null) {
    App::setLocale($lang);
    session()->put('locale', $lang);

    return redirect()->back();
})->name('setLang');

Route::middleware([HandleLanguage::class])->group(function () {

    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::put('/subscribe', [LandingController::class, 'subscribe'])->name('subscribe');
    Route::put('/contactUs', [LandingController::class, 'contactUs'])->name('contactMessages');

    Route::get('/WebsiteBuilder', [WebsiteBuilderController::class, 'index'])->name('WebsiteBuilder');

    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/settings.php';
    require __DIR__ . '/auth.php';
});
