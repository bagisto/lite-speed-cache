<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;
use Webkul\LSC\Http\Controllers\Admin\CacheController;
use Webkul\LSC\Http\Middleware\NoLiteSpeedCache;

Route::group(['middleware' => ['admin', NoCacheMiddleware::class, NoLiteSpeedCache::class], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('settings/litespeed-cache')->controller(CacheController::class)->group(function () {
        Route::get('', 'index')->name('admin.settings.lsc.index');

        Route::get('search/categories', 'searchCategories')->name('admin.settings.lsc.search.categories');

        Route::get('search/products', 'searchProducts')->name('admin.settings.lsc.search.products');

        Route::post('purge/all', 'purgeAll')->name('admin.settings.lsc.purge.all');

        Route::post('purge/home', 'purgeHome')->name('admin.settings.lsc.purge.home');

        Route::post('purge/category', 'purgeCategory')->name('admin.settings.lsc.purge.category');

        Route::post('purge/product', 'purgeProduct')->name('admin.settings.lsc.purge.product');

        Route::post('purge/tag', 'purgeTag')->name('admin.settings.lsc.purge.tag');

        Route::post('purge/url', 'purgeUrl')->name('admin.settings.lsc.purge.url');
    });
});
