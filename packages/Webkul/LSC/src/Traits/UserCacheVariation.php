<?php

namespace Webkul\LSC\Traits;

use Illuminate\Support\Facades\Cookie;

/**
 * Trait for generating consistent user isolation keys for LiteSpeed cache variation.
 * This ensures cart, wishlist, and compare data is properly isolated per user.
 * 
 * CRITICAL SECURITY: This trait handles user isolation to prevent cache leakage.
 */
trait UserCacheVariation
{
    /**
     * Track if vary cookie has been validated for current request.
     *
     * @var bool
     */
    protected static $varyCookieValidated = false;

    /**
     * Current request's vary key (cached for consistency).
     *
     * @var string|null
     */
    protected static $currentVaryKey = null;

    /**
     * Generate a unique vary key for the current user.
     * Uses customer ID for logged-in users, session ID for guests.
     * 
     * CRITICAL: This key MUST be stable within a session and unique per user.
     *
     * @return string
     */
    protected function generateVaryKey(): string
    {
        $customer = auth()->guard('customer')->user();
        
        if ($customer) {
            // Logged-in user: use customer ID (stable identifier)
            return 'user_' . $customer->id;
        }

        // Guest: use Laravel session ID (stable for session lifetime)
        $sessionId = session()->getId();
        
        // If no session exists yet, don't cache - return empty to trigger no-cache
        if (empty($sessionId)) {
            return '';
        }
        
        return 'guest_' . substr(hash('sha256', $sessionId . config('app.key')), 0, 16);
    }

    /**
     * Get the vary cookie name from config.
     *
     * @return string
     */
    protected function getVaryCookieName(): string
    {
        return config('lscache.vary_cookie', 'lsc_vary_key');
    }

    /**
     * Get the Laravel session cookie name.
     *
     * @return string
     */
    protected function getSessionCookieName(): string
    {
        return config('session.cookie', 'laravel_session');
    }

    /**
     * Set the vary cookie for the current user.
     * This cookie is used by LiteSpeed for X-LiteSpeed-Vary header.
     * 
     * CRITICAL: Cookie must be set BEFORE caching to ensure proper isolation.
     *
     * @return void
     */
    protected function setVaryCookie(): void
    {
        $cookieName = $this->getVaryCookieName();
        $newVaryKey = $this->generateVaryKey();
        $existingKey = request()->cookie($cookieName);
        
        // Cache the current vary key for this request
        self::$currentVaryKey = $newVaryKey;
        
        // Only update cookie if key changed (user logged in/out, new session)
        if ($existingKey !== $newVaryKey) {
            // Use setcookie() for immediate effect in current response
            // Cookie::queue() only takes effect on NEXT request which causes cache leakage
            $secure = config('session.secure', false);
            $sameSite = config('session.same_site', 'lax');
            $path = config('session.path', '/');
            $domain = config('session.domain');
            
            setcookie(
                $cookieName,
                $newVaryKey,
                [
                    'expires'  => time() + (60 * 60 * 24 * 30), // 30 days
                    'path'     => $path,
                    'domain'   => $domain,
                    'secure'   => $secure,
                    'httponly' => false, // Must be readable by LiteSpeed
                    'samesite' => $sameSite,
                ]
            );
            
            // Also queue for Laravel's cookie handling
            Cookie::queue($cookieName, $newVaryKey, 60 * 24 * 30, $path, $domain, $secure, false);
        }
        
        self::$varyCookieValidated = true;
    }

    /**
     * Get the current vary key from cookie or generate new one.
     * 
     * CRITICAL: Returns empty string if no valid vary key exists,
     * which should trigger no-cache to prevent cache leakage.
     *
     * @return string
     */
    protected function getVaryKey(): string
    {
        // Use cached key if available (ensures consistency within request)
        if (self::$currentVaryKey !== null) {
            return self::$currentVaryKey;
        }
        
        $cookieName = $this->getVaryCookieName();
        $cookieValue = request()->cookie($cookieName);
        
        if ($cookieValue) {
            self::$currentVaryKey = $cookieValue;
            return $cookieValue;
        }
        
        // No cookie exists - generate new key
        $newKey = $this->generateVaryKey();
        self::$currentVaryKey = $newKey;
        
        return $newKey;
    }

    /**
     * Check if the vary cookie exists and is valid for current user.
     * 
     * This returns true if:
     * 1. setVaryCookie() was called in this request (cookie validated/set), OR
     * 2. Incoming cookie matches current user state
     *
     * @return bool
     */
    protected function hasValidVaryCookie(): bool
    {
        // If we've already validated/set the cookie in this request, it's valid
        if (self::$varyCookieValidated && self::$currentVaryKey !== null) {
            return true;
        }

        $cookieName = $this->getVaryCookieName();
        $cookieValue = request()->cookie($cookieName);
        
        if (empty($cookieValue)) {
            return false;
        }
        
        // Verify cookie matches current user state
        $expectedKey = $this->generateVaryKey();
        
        return $cookieValue === $expectedKey;
    }

    /**
     * Generate cache tag for the current user.
     * Used for targeted cache purging.
     *
     * @return string
     */
    protected function getUserCacheTag(): string
    {
        $customer = auth()->guard('customer')->user();
        
        if ($customer) {
            return 'user_' . $customer->id;
        }

        $sessionId = session()->getId();
        if (empty($sessionId)) {
            return 'guest_anonymous';
        }

        return 'guest_' . substr(hash('sha256', $sessionId . config('app.key')), 0, 8);
    }

    /**
     * Get cache variation headers for currency and locale.
     *
     * @return array
     */
    protected function getCacheVariationValues(): array
    {
        return [
            'currency' => core()->getCurrentCurrencyCode(),
            'locale'   => core()->getCurrentLocale()->code ?? 'en',
            'channel'  => core()->getCurrentChannel()->code ?? 'default',
        ];
    }

    /**
     * Generate complete vary value for X-LiteSpeed-Vary header.
     *
     * @return string
     */
    protected function getCompleteVaryValue(): string
    {
        $varyKey = $this->getVaryKey();
        $variations = $this->getCacheVariationValues();
        
        return sprintf(
            'cookie=%s,currency=%s,locale=%s,channel=%s',
            $varyKey,
            $variations['currency'],
            $variations['locale'],
            $variations['channel']
        );
    }

    /**
     * Check if ESI is enabled in configuration.
     *
     * @return bool
     */
    protected function isEsiEnabled(): bool
    {
        return (bool) config('lscache.esi', false) 
            || (bool) core()->getConfigData('lsc.configuration.esi_settings.enabled');
    }

    /**
     * Get private cache TTL.
     *
     * @return int
     */
    protected function getPrivateCacheTTL(): int
    {
        return (int) config('lscache.private_ttl', 
            core()->getConfigData('lsc.configuration.esi_settings.private_ttl') ?? 300
        );
    }

    /**
     * Get ESI cache TTL.
     *
     * @return int
     */
    protected function getEsiCacheTTL(): int
    {
        return (int) config('lscache.esi_ttl', 
            core()->getConfigData('lsc.configuration.esi_settings.esi_ttl') ?? 300
        );
    }
}
