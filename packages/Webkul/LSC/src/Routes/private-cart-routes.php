<?php

use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;
use Webkul\Shop\Http\Controllers\API\CartController as ApiCartController;
use Webkul\Shop\Http\Controllers\CartController as StorefrontCartController;

/**
 * Override Bagisto cart read routes so the cart can be privately cached
 * without touching core package route files.
 */
Route::get('api/checkout/cart', [ApiCartController::class, 'index'])
    ->name('shop.api.checkout.cart.index')
    ->middleware(['lscache.cart.private:cart-api', DoNotCacheResponse::class]);

Route::get('api/checkout/cart/cross-sell', [ApiCartController::class, 'crossSellProducts'])
    ->name('shop.api.checkout.cart.cross-sell.index')
    ->middleware(['lscache.cart.private:cart-cross-sell']);

Route::get('checkout/cart', [StorefrontCartController::class, 'index'])
    ->name('shop.checkout.cart.index')
    ->middleware(['lscache.cart.private:cart-page']);
