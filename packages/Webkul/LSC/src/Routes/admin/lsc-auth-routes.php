<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Controllers\User\ForgetPasswordController;
use Webkul\Admin\Http\Controllers\User\ResetPasswordController;
use Webkul\Admin\Http\Controllers\User\SessionController;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;
use Webkul\LSC\Http\Middleware\NoLiteSpeedCache;

Route::group([
    'prefix'     => config('app.admin_url'),
    'middleware' => [NoCacheMiddleware::class, NoLiteSpeedCache::class],
], function () {
    Route::get('/', [Controller::class, 'redirectToLogin']);

    Route::controller(SessionController::class)->prefix('login')->group(function () {
        Route::get('', 'create')->name('admin.session.create');

        Route::post('', 'store')->name('admin.session.store');
    });

    Route::controller(ForgetPasswordController::class)->prefix('forget-password')->group(function () {
        Route::get('', 'create')->name('admin.forget_password.create');

        Route::post('', 'store')->name('admin.forget_password.store');
    });

    Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
        Route::get('{token}', 'create')->name('admin.reset_password.create');

        Route::post('', 'store')->name('admin.reset_password.store');
    });
});
