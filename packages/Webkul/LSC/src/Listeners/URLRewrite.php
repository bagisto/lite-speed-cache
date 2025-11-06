<?php

namespace Webkul\LSC\Listeners;

use LSCache;
use Webkul\Marketing\Repositories\URLRewriteRepository;

class URLRewrite
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected URLRewriteRepository $urlRewriteRepository) {}

    /**
     * After URL Rewrite update
     *
     * @param  \Webkul\Marketing\Contracts\URLRewrite  $urlRewrite
     * @return void
     */
    public function afterUpdate($urlRewrite)
    {
        $oldSlug = $urlRewrite->request_path;

        $tags = match ($urlRewrite->entity_type) {
            'product'  => ['product_'.$oldSlug],
            'cms_page' => ['page_'.$oldSlug],
            'category' => ['category_'.$oldSlug],
            default    => []
        };

        LSCache::purgeTags($tags);
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

        $oldSlug = $urlRewrite->request_path;

        $tags = match ($urlRewrite->entity_type) {
            'product'  => ['product_'.$oldSlug],
            'cms_page' => ['page_'.$oldSlug],
            'category' => ['category_'.$oldSlug],
            default    => []
        };

        LSCache::purgeTags($tags);
    }
}
