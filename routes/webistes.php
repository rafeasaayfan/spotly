<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::get('/e-commerce', function() {
    return Inertia::render('websites/e-commerce/spotly-ui/pages/home/Home');
});

Route::get('/shop', function() {
    return Inertia::render('websites/e-commerce/spotly-ui/pages/shop/Shop');
});