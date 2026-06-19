<?php

namespace Webkul\LSC\Providers;

use Illuminate\Routing\Router;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Admin\Http\Controllers\Catalog\CategoryController as BaseCategoryController;
use Webkul\Admin\Http\Controllers\Settings\ThemeController as BaseThemeController;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\LSC\Http\Controllers\Admin\Catalog\CategoryController;
use Webkul\LSC\Http\Controllers\Admin\Settings\ThemeController;
use Webkul\LSC\Http\Middleware\CustomerGroupCookie;
use Webkul\LSC\Http\Middleware\LSCacheHeaders;
use Webkul\LSC\Http\Middleware\NoLiteSpeedCache;
use Webkul\LSC\Http\Middleware\PrivateCartCache;
use Webkul\LSC\Http\Middleware\PrivateCompareCache;
use Webkul\LSC\Http\Middleware\PrivateVaryCookie;
use Webkul\LSC\Http\Middleware\PrivateWishlistCache;
use Webkul\LSC\Http\Middleware\PreventSensitiveRouteCaching;

class LSCServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        EncryptCookies::except(['lsc_private', 'lsc_customer_group']);

        $this->registerConfig();

        $this->registerCommands();

        $this->disableOtherCaches();
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'lsc');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'lsc');

        $this->app->register(EventServiceProvider::class);

        // Outermost of the LSC web middleware so its unwinding pass can read the
        // final X-LiteSpeed-Cache-Control (set by PreventSensitiveRouteCaching /
        // LSCacheHeaders) when deciding whether the response is publicly cached.
        $router->pushMiddlewareToGroup('web', PrivateVaryCookie::class);

        $router->pushMiddlewareToGroup('web', PreventSensitiveRouteCaching::class);

        $router->pushMiddlewareToGroup('web', CustomerGroupCookie::class);

        Route::middleware('web')->group(__DIR__.'/../Routes/admin/lsc-auth-routes.php');

        Route::middleware('web')->group(__DIR__.'/../Routes/admin/lsc-routes.php');

        $router->aliasMiddleware('no.lscache', NoLiteSpeedCache::class);

        $router->aliasMiddleware('lscache.response', LSCacheHeaders::class);
        $router->aliasMiddleware('lscache.cart.private', PrivateCartCache::class);
        $router->aliasMiddleware('lscache.compare.private', PrivateCompareCache::class);
        $router->aliasMiddleware('lscache.wishlist.private', PrivateWishlistCache::class);

        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/api.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/private-cart-routes.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/private-compare-routes.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/private-wishlist-routes.php');

        $this->app['view']->prependNamespace('shop', __DIR__.'/../Resources/views/shop');

        $this->app->bind(BaseCategoryController::class, CategoryController::class);
        $this->app->bind(BaseThemeController::class, ThemeController::class);

        $this->publishFiles();

        // Guard the boot-time DB read. core()->getConfigData() resolves the current channel
        // from the database; on a not-yet-installed store the channels table is missing
        // (before migrations) or empty (after migrations, before seeding), and getCurrentChannelCode()
        // then returns null against a string return type — a fatal that would break every
        // artisan command (migrate, install, seed). Swallow it until the store is installed.
        try {
            $lscActive = (bool) core()->getConfigData('lsc.configuration.cache_application.active');
        } catch (\Throwable $e) {
            return;
        }

        if ($lscActive) {
            $this->manageConfigMenus();
        }
    }

    /**
     * Disable other caching mechanisms when LSC is active.
     * This ensures only LiteSpeed Cache is used for the project.
     */
    protected function disableOtherCaches(): void
    {
        /**
         * Disable Laravel Response Cache when LSC package is installed
         */
        config(['responsecache.enabled' => false]);

        /**
         * Disable any other application-level caching that might conflict
         */
        // config(['cache.default' => 'array']);
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }

    /**
     * Manage the configuration of admin and supplier menus.
     */
    private function manageConfigMenus(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/admin/menu.php',
            'menu.admin'
        );
    }

    /**
     * Register the Installer Commands of this package.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webkul\LSC\Console\Commands\PurgeLiteSpeedCache::class,
                \Webkul\LSC\Console\Commands\InstallLiteSpeedCache::class,
            ]);
        }
    }

    /**
     * Publish the files.
     */
    protected function publishFiles(): void
    {
        $this->publishes([
            __DIR__.'/../Routes/admin/web.php' => __DIR__.'/../../../Admin/src/Routes/web.php',

            __DIR__.'/../Routes/admin/lsc-auth-routes.php' => __DIR__.'/../../../Admin/src/Routes/lsc-auth-routes.php',

            __DIR__.'/../Routes/shop/api.php' => __DIR__.'/../../../Shop/src/Routes/api.php',

            __DIR__.'/../Routes/shop/store-front-routes.php' => __DIR__.'/../../../Shop/src/Routes/store-front-routes.php',

            __DIR__.'/../Resources/views/shop/components/layouts/header' => resource_path('themes/default/views/components/layouts/header'),
        ]);
    }
}
