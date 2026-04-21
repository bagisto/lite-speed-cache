<?php

namespace Webkul\LSC\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Webkul\Shop\Http\Controllers\API\APIController;

class LoginContentController extends APIController
{
    /**
     * Render login content view and return as JSON.
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
