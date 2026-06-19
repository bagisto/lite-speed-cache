<?php

namespace Webkul\LSC\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Checkout\Facades\Cart;
use Webkul\LSC\Support\PrivateEsiCache;
use Webkul\Shop\Http\Controllers\API\APIController;

class CartCountController extends APIController
{
    /**
     * Render the mini-cart count badge as a raw-HTML ESI fragment.
     *
     * This seeds the cart badge server-side for an instant first paint (no
     * shimmer flash). The `v-mini-cart` Vue component still mounts over the
     * placeholder and takes over live, in-page updates (add/remove without a
     * reload), so this fragment only needs to be correct on full navigation —
     * which the cart-count purge on cart mutations guarantees.
     */
    public function index(Request $request): Response
    {
        $cart = Cart::getCart();

        $html = view('lsc::shop.home.cart-count', [
            'itemsQty'   => (int) ($cart?->items_qty ?? 0),
            'itemsCount' => (int) ($cart?->items_count ?? 0),
        ])->render();

        $response = response($html, Response::HTTP_OK)
            ->header('Content-Type', 'text/html; charset=UTF-8');

        return PrivateEsiCache::apply($response, $request, 'cart-count');
    }
}
