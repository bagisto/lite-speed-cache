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
     * After product create - purge home cache for new products
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        try {
            LSCache::purgeTags(['home']);

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
            $urls = $this->getForgettableUrls($product);

            if (! empty($urls)) {
                LSCache::purgeTags($urls);
            }
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

            $urls = $this->getForgettableUrls($product);

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
    public function getForgettableUrls($product)
    {
        $urls = [];

        $products = $this->getAllRelatedProducts($product);

        foreach ($products as $product) {
            if (! $product?->id) {
                continue;
            }

            $urls[] = 'product_'.$product->id;
        }

        return $urls;
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
