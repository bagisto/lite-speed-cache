<?php

use Illuminate\Support\Facades\Route;
use Webkul\LSC\Http\Controllers\API\CartCountController;
use Webkul\LSC\Http\Controllers\API\LoginContentController;
use Webkul\LSC\Http\Middleware\NoLiteSpeedCache;

/**
 * Login content routes (AJAX/Vue fallback path).
 *
 * Returns JSON and is never LiteSpeed-cached; the storefront injects the HTML
 * via innerHTML when ESI is disabled (e.g. OpenLiteSpeed / local dev).
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
 * ESI fragment routes (LiteSpeed Web Server Enterprise path).
 *
 * These return raw HTML and set their OWN private-cache headers, so they must
 * NOT carry NoLiteSpeedCache (which would force no-cache). PreventSensitiveRouteCaching
 * passes these `api/esi/*` routes through untouched, so the controller's
 * private headers reach LiteSpeed and each fragment is cached per-user.
 */
Route::group(['prefix' => 'api/esi'], function () {
    Route::controller(LoginContentController::class)->group(function () {
        Route::get('login-desktop-dropdown', 'esiLoginDesktopDropdown')
            ->name('shop.api.home.esi.login_desktop_dropdown');

        Route::get('login-mobile-dropdown', 'esiLoginMobileDropdown')
            ->name('shop.api.home.esi.login_mobile_dropdown');

        Route::get('login-mobile-drawer', 'esiLoginMobileDrawer')
            ->name('shop.api.home.esi.login_mobile_drawer');
    });

    Route::get('cart-count', [CartCountController::class, 'index'])
        ->name('shop.api.home.esi.cart_count');
});
