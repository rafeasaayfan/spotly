<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::get('/e-commerce', function() {
    return Inertia::render('websites/e-commerce/template-1/home/Home');
});

Route::get('/shop', function() {
    return Inertia::render('websites/e-commerce/template-1/shop/Shop');
});