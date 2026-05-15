<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class PurgeWishlistStateCache
{
    public function __construct(
        protected WishlistRepository $wishlistRepository,
    ) {}

    /**
     * Purge wishlist private cache plus affected product page tags after a successful mutation.
     */
    public function handle(Request $request, Closure $next)
    {
        $productIds = $this->resolveAffectedProductIds($request);

        $response = $next($request);

        if (
            in_array($request->getMethod(), ['GET', 'HEAD'], true)
            || $response->getStatusCode() >= 400
        ) {
            return $response;
        }

        $privateTags = CartCacheContext::currentPrivateTagsForFamily(
            'wishlist-private',
            ['wishlist-api', 'wishlist-page'],
            $request
        );

        LSCache::purgePrivateTags($privateTags);
        LSCache::purgeItems([
            '/api/customer/wishlist',
            '/customer/account/wishlist',
        ]);

        $productTags = array_map(
            static fn (int $productId): string => 'product_'.$productId,
            array_values(array_unique(array_filter($productIds)))
        );

        if ($productTags !== []) {
            LSCache::purgeTags($productTags);
        }

        $purgeParts = array_merge(
            ['stale', 'private'],
            array_map(static fn (string $tag): string => 'tag='.$tag, $privateTags),
            array_map(static fn (string $tag): string => 'tag='.$tag, $productTags),
            ['/api/customer/wishlist', '/customer/account/wishlist']
        );

        $response->headers->set('X-LiteSpeed-Purge', implode(',', $purgeParts));

        ResponseCache::forget('/api/customer/wishlist');
        ResponseCache::forget('/customer/account/wishlist');

        return $response;
    }

    /**
     * Resolve the affected product IDs before the mutation changes wishlist rows.
     *
     * @return array<int>
     */
    private function resolveAffectedProductIds(Request $request): array
    {
        if ($productId = (int) $request->input('product_id')) {
            return [$productId];
        }

        $customerId = (int) auth()->guard('customer')->id();

        if (! $customerId) {
            return [];
        }

        if ((string) $request->route()?->getName() === 'shop.api.customers.account.wishlist.destroy_all') {
            return $this->wishlistRepository
                ->where('customer_id', $customerId)
                ->pluck('product_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $wishlistId = (int) $request->route('id');

        if (! $wishlistId) {
            return [];
        }

        $wishlist = $this->wishlistRepository->find($wishlistId);

        return $wishlist?->product_id ? [(int) $wishlist->product_id] : [];
    }
}
