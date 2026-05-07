<?php

namespace Webkul\LSC\Listeners;

use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Wishlist
{
    /**
     * Wishlist item product IDs captured before destructive mutations.
     *
     * @var array<int>
     */
    private static array $pendingProductIds = [];

    public function __construct(
        protected WishlistRepository $wishlistRepository,
    ) {}

    /**
     * Capture product ID before a single wishlist item is removed or moved.
     */
    public function beforeChange(int $wishlistId): void
    {
        $wishlist = $this->wishlistRepository->find($wishlistId);

        if (! $wishlist?->product_id) {
            return;
        }

        self::$pendingProductIds[] = (int) $wishlist->product_id;
    }

    /**
     * Capture all wishlist product IDs before a bulk delete.
     */
    public function beforeDeleteAll(): void
    {
        $customerId = auth()->guard('customer')->id();

        if (! $customerId) {
            return;
        }

        $productIds = $this->wishlistRepository
            ->where('customer_id', $customerId)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        self::$pendingProductIds = array_merge(self::$pendingProductIds, $productIds);
    }

    /**
     * Purge private wishlist cache and affected product pages after a wishlist mutation.
     */
    public function afterChange(mixed $payload = null): void
    {
        LSCache::purgePrivateTags(
            CartCacheContext::currentPrivateTagsForFamily('wishlist-private', ['wishlist-api', 'wishlist-page'], request())
        );

        $productIds = self::$pendingProductIds;

        if (is_object($payload) && isset($payload->product_id)) {
            $productIds[] = (int) $payload->product_id;
        } elseif (is_numeric($payload)) {
            $productIds[] = (int) $payload;
        } elseif ($productId = request()->input('product_id')) {
            $productIds[] = (int) $productId;
        }

        $productTags = array_map(
            static fn (int $productId): string => 'product_'.$productId,
            array_values(array_unique(array_filter($productIds)))
        );

        if ($productTags !== []) {
            LSCache::purgeTags($productTags);
        }

        self::$pendingProductIds = [];
    }
}
