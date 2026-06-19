<?php

namespace Webkul\LSC\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\LSC\Support\PrivateEsiCache;
use Webkul\Shop\Http\Controllers\API\APIController;

class LoginContentController extends APIController
{
    /**
     * Private-cache scope shared by the three login fragments. A single scope
     * means one purge (on login/logout) invalidates all of them for a context.
     */
    private const ESI_SCOPE = 'login';

    /**
     * Render login content view and return as JSON.
     *
     * Used by the AJAX/Vue fallback path (OpenLiteSpeed / local dev), which
     * injects `data` via innerHTML.
     */
    private function renderLoginContent(string $viewName): JsonResponse
    {
        try {
            $html = view($viewName)->render();

            return response()->json([
                'data' => $html,
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => 'Unable to render view: '.$viewName,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Render a login fragment as raw HTML for an ESI include.
     *
     * LiteSpeed injects the response body verbatim into the cached page, so
     * this returns HTML (not the JSON wrapper) and carries private-cache
     * headers so the fragment is cached per-user at the edge.
     */
    private function renderEsiFragment(Request $request, string $viewName): Response
    {
        $html = view($viewName)->render();

        $response = response($html, Response::HTTP_OK)
            ->header('Content-Type', 'text/html; charset=UTF-8');

        return PrivateEsiCache::apply($response, $request, self::ESI_SCOPE);
    }

    /**
     * ESI: login dropdown content for desktop.
     */
    public function esiLoginDesktopDropdown(Request $request): Response
    {
        return $this->renderEsiFragment($request, 'lsc::shop.home.login-desktop-dropdown');
    }

    /**
     * ESI: login dropdown content for mobile.
     */
    public function esiLoginMobileDropdown(Request $request): Response
    {
        return $this->renderEsiFragment($request, 'lsc::shop.home.login-mobile-dropdown');
    }

    /**
     * ESI: login drawer content for mobile.
     */
    public function esiLoginMobileDrawer(Request $request): Response
    {
        return $this->renderEsiFragment($request, 'lsc::shop.home.login-mobile-drawer');
    }

    /**
     * Login dropdown content html for desktop.
     */
    public function getLoginDesktopDropdownHtml(): JsonResponse
    {
        return $this->renderLoginContent('lsc::shop.home.login-desktop-dropdown');
    }

    /**
     * Login dropdown content html for mobile.
     */
    public function getLoginMobileDropdownHtml(): JsonResponse
    {
        return $this->renderLoginContent('lsc::shop.home.login-mobile-dropdown');
    }

    /**
     * Login drawer content html for mobile.
     */
    public function getLoginMobileDrawerHtml(): JsonResponse
    {
        return $this->renderLoginContent('lsc::shop.home.login-mobile-drawer');
    }
}
