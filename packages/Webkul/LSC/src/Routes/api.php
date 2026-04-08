<?php

use Illuminate\Support\Facades\Route;
use Webkul\LSC\Http\Controllers\API\LoginContentController;
use Webkul\LSC\Http\Middleware\NoLiteSpeedCache;

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
