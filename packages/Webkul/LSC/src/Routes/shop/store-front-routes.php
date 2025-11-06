<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CartController;
use Webkul\Shop\Http\Controllers\BookingProductController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\PageController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;

/**
 * CMS pages.
 */
Route::middleware(['lscache.response', 'cache.response'])->group(function () {
    Route::get('page/{slug}', [PageController::class, 'view'])
        ->name('shop.cms.page');

    /**
     * Fallback route.
     */
    Route::fallback(ProductsCategoriesProxyController::class.'@index')
        ->name('shop.product_or_category.index');

    /**
     * Store front home.
     */
    Route::get('/', [HomeController::class, 'index'])
        ->name('shop.home.index');

    Route::get('contact-us', [HomeController::class, 'contactUs'])
        ->name('shop.home.contact_us');

    /**
     * Store front cart.
     */
    Route::get('api/checkout/cart', [CartController::class, 'index'])
        ->name('shop.api.checkout.cart.index');

    Route::post('api/checkout/cart', [CartController::class, 'store'])
        ->name('shop.api.checkout.cart.store');

    Route::delete('api/checkout/cart', [CartController::class, 'destroy'])
        ->name('shop.api.checkout.cart.destroy');

    /**
     * Store front search.
     */
    Route::get('search', [SearchController::class, 'index'])
        ->name('shop.search.index');

    /**
     * Compare products
     */
    Route::get('compare', [CompareController::class, 'index'])
        ->name('shop.compare.index');
});

/**
 * Contact us form submit route.
 */
Route::post('contact-us/send-mail', [HomeController::class, 'sendContactUsMail'])
    ->name('shop.home.contact_us.send_mail')
    ->middleware('cache.response');

/**
 * Search upload route.
 */
Route::post('search/upload', [SearchController::class, 'upload'])->name('shop.search.upload');

/**
 * Subscription routes.
 */
Route::controller(SubscriptionController::class)->group(function () {
    Route::post('subscription', 'store')->name('shop.subscription.store');

    Route::get('subscription/{token}', 'destroy')->name('shop.subscription.destroy');
});

/**
 * Downloadable products
 */
Route::controller(ProductController::class)->group(function () {
    Route::get('downloadable/download-sample/{type}/{id}', 'downloadSample')->name('shop.downloadable.download_sample');

    Route::get('product/{id}/{attribute_id}', 'download')->name('shop.product.file.download');
});

/**
 * Booking products
 */
Route::get('booking-slots/{id}', [BookingProductController::class, 'index'])
    ->name('shop.booking-product.slots.index');
