<?php

use App\Http\Controllers\Spotly\LandingController;
use App\Http\Controllers\Spotly\WebsiteBuilderController;
use App\Http\Controllers\Spotly\WebsitePreviewController;
use App\Http\Middleware\HandleLanguage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
    require __DIR__ . '/websites/e-commerce/web.php';
});

// Dashboards Routes function
function dashboardPagesRoutes($name, $controller)
{
    $param = Str::singular($name);

    Route::resource($name, $controller)->except(['destroy', 'update']);
    Route::post($name . '/{' . $param . '}/update', [$controller, 'update'])->name($name . '.update');
    Route::post($name . '/destroy', [$controller, 'destroy'])->name($name . '.destroy');
}
