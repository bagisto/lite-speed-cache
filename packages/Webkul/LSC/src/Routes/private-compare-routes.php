<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CompareController as ApiCompareController;
use Webkul\Shop\Http\Controllers\CompareController as StorefrontCompareController;

/**
 * Override Bagisto compare read routes so compare can be privately cached
 * without disturbing the existing cart private cache flow.
 */
Route::get('api/compare-items', [ApiCompareController::class, 'index'])
    ->name('shop.api.compare.index')
    ->middleware(['lscache.compare.private:compare-api']);

Route::get('compare', [StorefrontCompareController::class, 'index'])
    ->name('shop.compare.index')
    ->middleware(['lscache.compare.private:compare-page']);
