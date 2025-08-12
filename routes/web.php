<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\WebsiteBuilderController;
use App\Http\Controllers\WebsitePreviewController;
use App\Http\Middleware\HandleLanguage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/setLang/{lang}', function ($lang = null) {
    App::setLocale($lang);
    session()->put('locale', $lang);

    return redirect()->back();
})->name('setLang');

Route::middleware([HandleLanguage::class])->group(function () {

    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::put('/subscribe', [LandingController::class, 'subscribe'])->name('subscribe');
    Route::put('/contactUs', [LandingController::class, 'contactUs'])->name('contactMessages');

    // WebsiteBuilder
    Route::prefix('websiteBuilder')->name('websiteBuilder.')->group(function() {
        Route::get('/', [WebsiteBuilderController::class, 'index'])->name('index');
        Route::post('/wizard/{step}', [WebsiteBuilderController::class, 'wizard'])->name('wizard');

        // UI 
        Route::post('/getTemplateTemplateColors', [WebsiteBuilderController::class, 'getTemplateTemplateColors'])->name('getTemplateTemplateColors');
        Route::post('/customColors', [WebsiteBuilderController::class, 'customColors'])->name('customColors');
    });


    // Preview
    Route::post('/websites/preview', [WebsitePreviewController::class, 'preview'])->name('website.preview');

    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/settings.php';
    require __DIR__ . '/auth.php';
    require __DIR__ . '/webistes.php';
});
