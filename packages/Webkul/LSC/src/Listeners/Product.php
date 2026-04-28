<?php

namespace Webkul\LSC\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;
use Webkul\LSC\Traits\DeletesAllCache;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class Product
{
    use DeletesAllCache;

    /**
     * Track product category assignments before update so old category listing caches can be purged too.
     *
     * @var array<int, array<int, int>>
     */
    private static array $oldCategoryIdsByProductId = [];

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository
    ) {}

    /**
     * Capture category assignments before update so removed category listing caches can be purged too.
     */
    public function beforeUpdate($productId): void
    {
        $product = $this->productRepository->find($productId);

        if (! $product) {
            return;
        }

        self::$oldCategoryIdsByProductId[$productId] = $this->getCategoryIds($product);
    }

    /**
     * After product create - purge home cache for new products
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        try {
            LSCache::purgeTags(array_merge(['home'], $this->getCategoryTags($product)));

            $this->deletePrivCache();
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache after product creation', [
                'product_id' => $product->id ?? null,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update or create product page cache
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        try {
            $oldCategoryIds = self::$oldCategoryIdsByProductId[$product->id] ?? [];
            $urls = $this->getForgettableUrls($product, $oldCategoryIds);

            if (! empty($urls)) {
                LSCache::purgeTags($urls);
            }

            unset(self::$oldCategoryIdsByProductId[$product->id]);
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache after product update', [
                'product_id' => $product->id ?? null,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete product page cache
     *
     * @param  int  $productId
     * @return void
     */
    public function beforeDelete($productId)
    {
        try {
            $product = $this->productRepository->find($productId);

            if (! $product) {
                Log::warning('LSCache: Product not found for cache deletion', ['product_id' => $productId]);

                return;
            }

            $urls = $this->getForgettableUrls($product, $this->getCategoryIds($product));

            if (! empty($urls)) {
                LSCache::purgeTags($urls);
            }
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache before product deletion', [
                'product_id' => $productId,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Returns product urls
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getForgettableUrls($product, array $extraCategoryIds = [])
    {
        $urls = [];

        $products = $this->getAllRelatedProducts($product);

        foreach ($products as $product) {
            if (! $product?->id) {
                continue;
            }

            $urls[] = 'product_'.$product->id;

            $urls = array_merge($urls, $this->getCategoryTags($product, $extraCategoryIds));
        }

        return $urls;
    }

    /**
     * Resolve the category listing tags that can contain this product.
     */
    private function getCategoryTags($product, array $extraCategoryIds = []): array
    {
        $categoryIds = array_unique(array_merge($this->getCategoryIds($product), $extraCategoryIds));

        return array_map(
            fn (int $categoryId) => 'category-products_'.$categoryId,
            array_values(array_filter($categoryIds))
        );
    }

    /**
     * Resolve current category IDs for the product.
     */
    private function getCategoryIds($product): array
    {
        return $product->categories()
            ->pluck('categories.id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Returns parents bundle products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    private function getAllRelatedProducts($product)
    {
        $products = [$product];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $products[] = $product->parent;
            }

            $products = array_merge(
                $products,
                $this->getParentBundleProducts($product),
                $this->getParentGroupProducts($product)
            );
        } elseif ($product->type == 'configurable') {
            $products = [];

            /**
             * Fetching fresh variants.
             */
            foreach ($product->variants()->get() as $variant) {
                $products[] = $variant;
            }

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Returns parents bundle products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    private function getParentBundleProducts($product)
    {
        $bundleOptionProducts = $this->productBundleOptionProductRepository->findWhere([
            'product_id' => $product->id,
        ]);

        $products = [];

        foreach ($bundleOptionProducts as $bundleOptionProduct) {
            $products[] = $bundleOptionProduct->bundle_option->product;
        }

        return $products;
    }

    /**
     * Returns parents group products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    private function getParentGroupProducts($product)
    {
        $groupedOptionProducts = $this->productGroupedProductRepository->findWhere([
            'associated_product_id' => $product->id,
        ]);

        $products = [];

        foreach ($groupedOptionProducts as $groupedOptionProduct) {
            $products[] = $groupedOptionProduct->product;
        }

        return $products;
    }
}
