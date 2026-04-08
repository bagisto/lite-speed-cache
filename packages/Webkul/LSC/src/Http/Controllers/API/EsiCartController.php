<?php

namespace Webkul\LSC\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shop\Http\Controllers\API\APIController;
use Webkul\LSC\Traits\UserCacheVariation;

/**
 * ESI Cart Controller
 * 
 * Handles ESI (Edge Side Includes) requests for cart-related components.
 * These endpoints return user-specific cart data with private cache headers,
 * allowing LiteSpeed to cache cart data per-user while the main page remains publicly cached.
 */
class EsiCartController extends APIController
{
    use UserCacheVariation;

    /**
     * ESI endpoint for mini cart content.
     * Returns cart items HTML for inclusion via ESI.
     *
     * @return \Illuminate\Http\Response
     */
    public function miniCart(): Response
    {
        $cart = Cart::getCart();
        
        $html = view('lsc::shop.esi.mini-cart', [
            'cart' => $cart,
        ])->render();

        return $this->createEsiResponse($html, ['mini-cart', $this->getUserCacheTag()]);
    }

    /**
     * ESI endpoint for cart count badge.
     * Returns just the cart count/quantity for header display.
     *
     * @return \Illuminate\Http\Response
     */
    public function cartCount(): Response
    {
        $cart = Cart::getCart();
        
        $html = view('lsc::shop.esi.cart-count', [
            'cart' => $cart,
        ])->render();

        return $this->createEsiResponse($html, ['cart-count', $this->getUserCacheTag()]);
    }

    /**
     * ESI endpoint for cart data as JSON.
     * Returns full cart data for JavaScript consumption.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartData(): JsonResponse
    {
        $cart = Cart::getCart();
        
        $response = response()->json([
            'data' => $cart ? $this->formatCartData($cart) : null,
        ]);

        return $this->addPrivateCacheHeaders($response, ['cart-data', $this->getUserCacheTag()]);
    }

    /**
     * ESI endpoint for full cart page content.
     * Returns cart items list for the cart page.
     *
     * @return \Illuminate\Http\Response
     */
    public function cartPage(): Response
    {
        $cart = Cart::getCart();
        
        $html = view('lsc::shop.esi.cart-page', [
            'cart' => $cart,
        ])->render();

        return $this->createEsiResponse($html, ['cart-page', $this->getUserCacheTag()]);
    }

    /**
     * Create an ESI response with proper LiteSpeed private cache headers.
     * CRITICAL: Includes laravel_session to prevent cross-user cache leakage.
     *
     * @param  string  $html
     * @param  array   $tags
     * @return \Illuminate\Http\Response
     */
    protected function createEsiResponse(string $html, array $tags = []): Response
    {
        $ttl = $this->getEsiCacheTTL();
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();
        
        $response = response($html);
        
        // CRITICAL: Include session cookie for proper user isolation
        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl},esi=on");
        $response->headers->set('X-LiteSpeed-Vary', "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency");
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        
        // Standard cache headers
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");
        
        // Ensure vary cookie is set
        $this->setVaryCookie();
        
        return $response;
    }

    /**
     * Add private cache headers to a JSON response.
     * CRITICAL: Includes laravel_session to prevent cross-user cache leakage.
     *
     * @param  \Illuminate\Http\JsonResponse  $response
     * @param  array  $tags
     * @return \Illuminate\Http\JsonResponse
     */
    protected function addPrivateCacheHeaders(JsonResponse $response, array $tags = []): JsonResponse
    {
        $ttl = $this->getPrivateCacheTTL();
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();
        
        // CRITICAL: Include session cookie for proper user isolation
        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Vary', "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency");
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");
        
        // Ensure vary cookie is set
        $this->setVaryCookie();
        
        return $response;
    }

    /**
     * Format cart data for JSON response.
     *
     * @param  mixed  $cart
     * @return array
     */
    protected function formatCartData($cart): array
    {
        return [
            'id'                        => $cart->id,
            'items_count'               => $cart->items_count,
            'items_qty'                 => $cart->items_qty,
            'sub_total'                 => core()->formatPrice($cart->sub_total),
            'grand_total'               => core()->formatPrice($cart->grand_total),
            'formatted_sub_total'       => core()->formatPrice($cart->sub_total),
            'formatted_grand_total'     => core()->formatPrice($cart->grand_total),
            'items'                     => $this->formatCartItems($cart->items),
        ];
    }

    /**
     * Format cart items for response.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return array
     */
    protected function formatCartItems($items): array
    {
        return $items->map(function ($item) {
            return [
                'id'                    => $item->id,
                'name'                  => $item->name,
                'quantity'              => $item->quantity,
                'price'                 => $item->price,
                'formatted_price'       => core()->formatPrice($item->price),
                'total'                 => $item->total,
                'formatted_total'       => core()->formatPrice($item->total),
                'product_url_key'       => $item->product->url_key ?? '',
                'base_image'            => [
                    'small_image_url'   => $item->product?->base_image_url ?? '',
                ],
            ];
        })->toArray();
    }
}
