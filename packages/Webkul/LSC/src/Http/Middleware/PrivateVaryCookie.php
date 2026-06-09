<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Webkul\LSC\Support\CartCacheContext;

class PrivateVaryCookie
{
    /**
     * Keep the unencrypted `lsc_private` vary cookie in sync with the current
     * identity so LiteSpeed serves the correct private ESI fragments
     * (login dropdown, cart-count badge) after login / logout.
     *
     * The private ESI fragments are stored keyed by `lsc_private`
     * (X-LiteSpeed-Vary: cookie=lsc_private). That cookie is otherwise only
     * emitted as a side effect of ESI / private-cart responses — which are
     * served from cache (no PHP) on a hit, and whose Set-Cookie never reaches
     * the browser when produced by an ESI sub-request. So on login/logout the
     * browser keeps presenting the PREVIOUS identity's vary value and LiteSpeed
     * keeps serving the previous identity's cached header fragment.
     *
     * This middleware is the private-cache counterpart of CustomerGroupCookie
     * (which syncs the public vary `lsc_customer_group`): whenever the visitor's
     * expected `lsc_private` value differs from what the browser sent, rewrite
     * it. On the login/logout redirects — which are `no-cache` — this flips the
     * cookie immediately, so the next navigation selects the correct ESI bucket.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only meaningful while LSC private caching is active; otherwise the
        // vary cookie has no consumer.
        if (! CartCacheContext::privateCacheEnabled()) {
            return $response;
        }

        // NEVER set this cookie on a publicly cacheable response. The public
        // pages (home, product, category) are varied only by lsc_customer_group,
        // NOT by lsc_private, so a Set-Cookie baked into that shared cache entry
        // would hand one visitor's private vary to every other visitor in the
        // same group — leaking their cart / account fragments. Auth transitions
        // happen on no-cache responses, which are safe to write to.
        $cacheControl = (string) $response->headers->get('X-LiteSpeed-Cache-Control');

        if (str_contains($cacheControl, 'public')) {
            return $response;
        }

        $cookieName = CartCacheContext::privateCookieName();
        $expected = CartCacheContext::privateCookieValue($request);

        if ((string) $request->cookie($cookieName) === $expected) {
            return $response;
        }

        $response->headers->setCookie(new Cookie(
            $cookieName,
            $expected,
            0,
            '/',
            config('session.domain'),
            (bool) config('session.secure'),
            false,
            false,
            config('session.same_site', 'lax')
        ));

        return $response;
    }
}
