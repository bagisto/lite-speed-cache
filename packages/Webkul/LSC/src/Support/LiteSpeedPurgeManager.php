<?php

namespace Webkul\LSC\Support;

use Illuminate\Support\Facades\Log;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class LiteSpeedPurgeManager
{
    /**
     * Create a new manager instance.
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
    ) {}

    /**
     * Purge all LiteSpeed cache.
     */
    public function purgeAll(): array
    {
        DebuggableLSCache::purgeAll();

        return [
            'header'  => '*',
            'message' => trans('lsc::app.admin.flash.purge-all'),
            'log'     => ['all' => true],
        ];
    }

    /**
     * Purge the home page tags.
     */
    public function purgeHome(): array
    {
        $tags = ['home', 'home-header'];

        DebuggableLSCache::purgeTags($tags);

        return [
            'header'  => $this->buildTagHeader($tags),
            'message' => trans('lsc::app.admin.flash.home'),
            'log'     => ['tags' => $tags],
        ];
    }

    /**
     * Purge a category by stable ID-based tag.
     */
    public function purgeCategory(int $categoryId): array
    {
        $category = $this->categoryRepository->findOrFail($categoryId);
        $tags = ['category_'.$category->id];

        DebuggableLSCache::purgeTags($tags);

        return [
            'header'  => $this->buildTagHeader($tags),
            'message' => trans('lsc::app.admin.flash.category', ['name' => $category->name]),
            'log'     => ['category_id' => $category->id, 'tags' => $tags],
        ];
    }

    /**
     * Purge a product by stable ID-based tag.
     */
    public function purgeProduct(int $productId): array
    {
        $product = $this->productRepository->findOrFail($productId);
        $tags = ['product_'.$product->id];

        DebuggableLSCache::purgeTags($tags);

        return [
            'header'  => $this->buildTagHeader($tags),
            'message' => trans('lsc::app.admin.flash.product', ['name' => $product->name]),
            'log'     => ['product_id' => $product->id, 'tags' => $tags],
        ];
    }

    /**
     * Purge one or more manual tags.
     */
    public function purgeTags(array $tags): array
    {
        $tags = $this->sanitizeTags($tags);

        DebuggableLSCache::purgeTags($tags);

        return [
            'header'  => $this->buildTagHeader($tags),
            'message' => trans('lsc::app.admin.flash.tags'),
            'log'     => ['tags' => $tags],
        ];
    }

    /**
     * Purge one exact URL path.
     */
    public function purgeUrl(string $url): array
    {
        $path = $this->normalizeUrlToPath($url);

        DebuggableLSCache::purgeItems([$path]);

        return [
            'header'  => $path,
            'message' => trans('lsc::app.admin.flash.url', ['url' => $path]),
            'log'     => ['url' => $path],
        ];
    }

    /**
     * Build the header value for tag purges.
     */
    public function buildTagHeader(array $tags): string
    {
        return implode(',', array_map(static fn (string $tag): string => 'tag='.$tag, $this->sanitizeTags($tags)));
    }

    /**
     * Normalize tag input.
     */
    protected function sanitizeTags(array $tags): array
    {
        return array_values(array_unique(array_filter(array_map(
            static fn ($tag): string => trim((string) $tag),
            $tags
        ))));
    }

    /**
     * Convert an absolute or relative URL to a purgeable path.
     */
    protected function normalizeUrlToPath(string $url): string
    {
        $url = trim($url);

        $path = parse_url($url, PHP_URL_PATH);

        if ($path === null || $path === false || $path === '') {
            $path = $url;
        }

        $query = parse_url($url, PHP_URL_QUERY);

        $path = '/'.ltrim($path, '/');

        if ($query) {
            $path .= '?'.$query;
        }

        return $path;
    }

    /**
     * Write a structured audit log entry.
     */
    public function logAction(string $action, array $context = []): void
    {
        Log::info('LSCache admin purge action', array_merge([
            'action'   => $action,
            'admin_id' => auth()->guard('admin')->id(),
        ], $context));
    }
}
