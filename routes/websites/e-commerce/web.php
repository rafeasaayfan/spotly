<?php

use App\Http\Controllers\Websites\Ecommerce\CartController;
use App\Http\Controllers\Websites\Ecommerce\HomeController;
use App\Http\Controllers\Websites\Ecommerce\ProductController;
use App\Http\Controllers\Websites\Ecommerce\ShopController;
use Illuminate\Support\Facades\Route;

Route::name('e-commerce.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::put('/contactUs', [HomeController::class, 'contactUs'])->name('contactMessages');

    Route::get('/shop', [ShopController::class, 'index'])->name('shop');

    Route::get('/product/{slug}', [ProductController::class, 'index'])->name('product');
    Route::post('/addToCart', [ProductController::class, 'addToCart'])->name('addToCart');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::delete('/cart/removeItem', [CartController::class, 'removeItem'])->name('cart.removeItem');

    Route::middleware(['auth:website', 'verified:website.verification.notice', 'websiteUserRole:admin'])->group(function () {
        require __DIR__ . '/dashboard.php';
        require __DIR__ . '/../common/dashboard.php';
    });
});
