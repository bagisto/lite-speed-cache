<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Webkul\LSC\Support\CartCacheContext;

class CustomerGroupCookie
{
    /**
     * Keep the unencrypted customer-group cookie in sync so LiteSpeed can vary
     * the public cache per customer group (guest / general / wholesale).
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $cookieName = CartCacheContext::groupCookieName();
        $groupCode = CartCacheContext::currentGroupCode();

        if ((string) $request->cookie($cookieName) !== $groupCode) {
            $response->headers->setCookie(new Cookie(
                $cookieName,
                $groupCode,
                strtotime('+5 years'),
                '/',
                config('session.domain'),
                (bool) config('session.secure'),
                false,
                false,
                config('session.same_site', 'lax')
            ));
        }

        return $response;
    }
}
