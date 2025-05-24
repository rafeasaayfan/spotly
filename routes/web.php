<?php

use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/setLang/{lang}', function ($lang = null) {
    App::setLocale($lang);
    session()->put('locale', $lang);

    return redirect()->back();
})->name('setLang');

Route::middleware([LanguageMiddleware::class])->group(function () {

    Route::get('/', function () {
        return Inertia::render('Landing');
    })->name('landing');

    Route::get('/home', function () {
        return Inertia::render('Home');
    })->name('home');

    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/settings.php';
    require __DIR__ . '/auth.php';
});
