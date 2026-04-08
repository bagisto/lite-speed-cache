<?php

use Illuminate\Support\Facades\Route;
use Webkul\LSC\Http\Controllers\API\LoginContentController;
use Webkul\LSC\Http\Controllers\API\EsiCartController;
use Webkul\LSC\Http\Middleware\NoLiteSpeedCache;
use Webkul\LSC\Http\Middleware\EsiCacheMiddleware;

/**
 * Login content routes.
 */
Route::group(['middleware' => [NoLiteSpeedCache::class], 'prefix' => 'api'], function () {
    Route::controller(LoginContentController::class)->group(function () {
        Route::get('login-desktop-dropdown', 'getLoginDesktopDropdownHtml')
            ->name('shop.api.home.login_desktop_dropdown');

        Route::get('login-mobile-dropdown', 'getLoginMobileDropdownHtml')
            ->name('shop.api.home.login_mobile_dropdown');

        Route::get('login-mobile-drawer', 'getLoginMobileDrawerHtml')
            ->name('shop.api.home.login_mobile_drawer');
    });
});

/**
 * ESI (Edge Side Includes) routes.
 * These endpoints return user-specific content with private cache headers.
 * LiteSpeed caches these per-user while the main page remains publicly cached.
 */
Route::group(['middleware' => [EsiCacheMiddleware::class], 'prefix' => 'esi'], function () {
    Route::controller(EsiCartController::class)->group(function () {
        // Mini cart ESI - returns cart items HTML for header mini cart
        Route::get('mini-cart', 'miniCart')
            ->name('shop.lsc.esi.mini_cart');

        // Cart count ESI - returns just the cart count badge
        Route::get('cart-count', 'cartCount')
            ->name('shop.lsc.esi.cart_count');

        // Cart data ESI - returns full cart data as JSON
        Route::get('cart-data', 'cartData')
            ->name('shop.lsc.esi.cart_data');

        // Cart page ESI - returns cart page content
        Route::get('cart-page', 'cartPage')
            ->name('shop.lsc.esi.cart_page');
    });
});

/**
 * API ESI routes (alternative path for ESI content).
 */
Route::group(['middleware' => [EsiCacheMiddleware::class], 'prefix' => 'api/esi'], function () {
    Route::controller(EsiCartController::class)->group(function () {
        Route::get('mini-cart', 'miniCart')
            ->name('shop.api.lsc.esi.mini_cart');

        Route::get('cart-count', 'cartCount')
            ->name('shop.api.lsc.esi.cart_count');

        Route::get('cart-data', 'cartData')
            ->name('shop.api.lsc.esi.cart_data');

        Route::get('cart-page', 'cartPage')
            ->name('shop.api.lsc.esi.cart_page');
    });
});
