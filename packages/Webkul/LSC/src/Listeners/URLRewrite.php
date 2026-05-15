<?php

namespace Webkul\LSC\Listeners;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\CMS\Repositories\PageRepository;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;
use Webkul\Marketing\Repositories\URLRewriteRepository;
use Webkul\Product\Repositories\ProductRepository;

class URLRewrite
{
    /**
     * Snapshot of the rewrite before an update mutates the record.
     */
    protected ?object $originalUrlRewrite = null;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected URLRewriteRepository $urlRewriteRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
        protected PageRepository $pageRepository
    ) {}

    /**
     * Before URL Rewrite update.
     *
     * @param  int  $urlRewriteId
     * @return void
     */
    public function beforeUpdate($urlRewriteId)
    {
        $this->originalUrlRewrite = $this->urlRewriteRepository->find($urlRewriteId);
    }

    /**
     * After URL Rewrite update
     *
     * @param  \Webkul\Marketing\Contracts\URLRewrite  $urlRewrite
     * @return void
     */
    public function afterUpdate($urlRewrite)
    {
        $tags = array_merge(
            $this->getTagsForRewrite($this->originalUrlRewrite),
            $this->getTagsForRewrite($urlRewrite)
        );

        if ($tags !== []) {
            LSCache::purgeTags(array_values(array_unique($tags)));
        }

        $this->originalUrlRewrite = null;
    }

    /**
     * Before URL Rewrite delete
     *
     * @param  int  $urlRewriteId
     * @return void
     */
    public function beforeDelete($urlRewriteId)
    {
        $urlRewrite = $this->urlRewriteRepository->find($urlRewriteId);

        $tags = $this->getTagsForRewrite($urlRewrite);

        if ($tags !== []) {
            LSCache::purgeTags(array_values(array_unique($tags)));
        }
    }

    /**
     * Resolve stable cache tags for a rewrite record.
     */
    protected function getTagsForRewrite($urlRewrite): array
    {
        if (! $urlRewrite) {
            return [];
        }

        return match ($urlRewrite->entity_type) {
            'product'  => $this->getProductTags($urlRewrite),
            'cms_page' => $this->getPageTags($urlRewrite),
            'category' => $this->getCategoryTags($urlRewrite),
            default    => [],
        };
    }

    /**
     * Resolve product tags from either side of the rewrite.
     */
    protected function getProductTags($urlRewrite): array
    {
        foreach ([$urlRewrite->target_path, $urlRewrite->request_path] as $path) {
            if ($product = $this->productRepository->findBySlug($path)) {
                return ['product_'.$product->id];
            }
        }

        return [];
    }

    /**
     * Resolve category tags from either side of the rewrite.
     */
    protected function getCategoryTags($urlRewrite): array
    {
        foreach ([$urlRewrite->target_path, $urlRewrite->request_path] as $path) {
            if ($category = $this->categoryRepository->findBySlug($path)) {
                return ['category_'.$category->id];
            }
        }

        return [];
    }

    /**
     * Resolve CMS page tags from either side of the rewrite.
     */
    protected function getPageTags($urlRewrite): array
    {
        foreach ([$urlRewrite->target_path, $urlRewrite->request_path] as $path) {
            if ($page = $this->pageRepository->findOneWhere(['url_key' => $path])) {
                return ['page_'.$page->id];
            }
        }

        return [];
    }
}
