<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;
use Webkul\Shop\Http\Controllers\API\WishlistController as ApiWishlistController;
use Webkul\Shop\Http\Controllers\Customer\Account\WishlistController as StorefrontWishlistController;

/**
 * Override Bagisto wishlist read routes so wishlist can be privately cached
 * without disturbing the existing cart and compare private cache flows.
 */
Route::middleware(['customer'])->group(function () {
    Route::get('api/customer/wishlist', [ApiWishlistController::class, 'index'])
        ->name('shop.api.customers.account.wishlist.index')
        ->middleware(['lscache.wishlist.private:wishlist-api']);
});

Route::middleware(['customer', NoCacheMiddleware::class])->group(function () {
    Route::get('customer/account/wishlist', [StorefrontWishlistController::class, 'index'])
        ->name('shop.customers.account.wishlist.index')
        ->middleware(['lscache.wishlist.private:wishlist-page']);
});
