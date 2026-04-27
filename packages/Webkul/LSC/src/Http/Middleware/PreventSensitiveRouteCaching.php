<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\LiteSpeedDebug;

class PreventSensitiveRouteCaching
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $this->shouldBypassCache($request)) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return LiteSpeedDebug::attachToResponse($response, [], 'no-cache');
    }

    /**
     * Sensitive routes and authenticated sessions must never be cached.
     */
    private function shouldBypassCache(Request $request): bool
    {
        if (! in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            return true;
        }

        $routeName = (string) $request->route()?->getName();
        $adminPrefix = trim((string) config('app.admin_url', 'admin'), '/');
        $isAdminRoute = str_starts_with($routeName, 'admin.')
            || ($adminPrefix !== '' && ($request->is($adminPrefix) || $request->is($adminPrefix.'/*')));

        if ($isAdminRoute && auth()->guard('admin')->check()) {
            return true;
        }

        if (auth()->guard('customer')->check()) {
            return true;
        }

        return $isAdminRoute
            || $request->is('login')
            || $request->is('logout')
            || $request->is('checkout')
            || $request->is('checkout/*')
            || $request->is('cart')
            || $request->is('cart/*')
            || $request->is('customer')
            || $request->is('customer/*');
    }
}
