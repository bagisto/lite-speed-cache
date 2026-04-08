<?php

namespace Webkul\LSC\Listeners;

use Illuminate\Support\Facades\Cookie;
use LSCache;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\LSC\Traits\DeletesAllCache;
use Webkul\LSC\Traits\UserCacheVariation;

class CustomerSession
{
    use DeletesAllCache;
    use UserCacheVariation;

    /**
     * After customer session create (login).
     * CRITICAL: This handles cache isolation when user logs in.
     *
     * @return void
     */
    public function afterCreate()
    {
        // Get old guest tag for purging
        $oldTag = 'guest_' . substr(hash('sha256', session()->getId() . config('app.key')), 0, 8);

        $this->deletePrivCache();

        // Purge public cache tags
        LSCache::purgeTags(['home', 'home-header']);
        
        // Purge cart-related items
        LSCache::purgeItems([
            '/api/checkout/cart',
            '/esi/mini-cart',
            '/esi/cart-count',
            '/esi/cart-data',
            '/checkout/cart',
        ]);

        // Purge old guest cart and new user cart
        LSCache::purgeTags(['cart', 'mini-cart', 'cart-count', $oldTag]);

        // Purge user-specific cart tags
        $newUserTag = $this->getUserCacheTag();
        LSCache::purgeTags([$newUserTag]);

        ResponseCache::forget('/api/checkout/cart');

        // CRITICAL: Clear old vary cookie and set new one for logged-in user
        $this->clearAndSetVaryCookie();
    }

    /**
     * After customer session destroy (logout).
     * CRITICAL: This handles cache isolation when user logs out.
     *
     * @return void
     */
    public function afterDestroy()
    {
        // Get user tag before logout for purging
        $userTag = $this->getUserCacheTag();

        $this->deletePrivCache();

        // Purge public cache tags
        LSCache::purgeTags(['home', 'home-header']);
        
        // Purge cart-related items
        LSCache::purgeItems([
            '/api/checkout/cart',
            '/esi/mini-cart',
            '/esi/cart-count',
            '/esi/cart-data',
            '/checkout/cart',
        ]);

        // Purge user-specific cart tags
        LSCache::purgeTags(['cart', 'mini-cart', 'cart-count', $userTag]);
        
        ResponseCache::forget('/api/checkout/cart');

        // CRITICAL: Clear vary cookie on logout to prevent cache reuse
        $this->clearVaryCookie();
    }

    /**
     * Clear the vary cookie.
     * CRITICAL: This ensures cache is not reused after logout.
     *
     * @return void
     */
    protected function clearVaryCookie(): void
    {
        $cookieName = $this->getVaryCookieName();
        
        // Expire the cookie immediately
        Cookie::queue(Cookie::forget($cookieName));
        
        // Also use setcookie for immediate effect
        setcookie($cookieName, '', time() - 3600, '/', config('session.domain'));
    }

    /**
     * Clear old cookie and set new one.
     * Used during login to switch from guest to user isolation.
     *
     * @return void
     */
    protected function clearAndSetVaryCookie(): void
    {
        $cookieName = $this->getVaryCookieName();
        $newVaryKey = $this->generateVaryKey();
        
        $secure = config('session.secure', false);
        $sameSite = config('session.same_site', 'lax');
        $path = config('session.path', '/');
        $domain = config('session.domain');
        
        // Set new cookie immediately
        setcookie(
            $cookieName,
            $newVaryKey,
            [
                'expires'  => time() + (60 * 60 * 24 * 30),
                'path'     => $path,
                'domain'   => $domain,
                'secure'   => $secure,
                'httponly' => false,
                'samesite' => $sameSite,
            ]
        );
        
        // Also queue for Laravel
        Cookie::queue($cookieName, $newVaryKey, 60 * 24 * 30, $path, $domain, $secure, false);
    }
}
