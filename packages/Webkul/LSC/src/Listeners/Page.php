<?php

namespace Webkul\LSC\Listeners;

use Webkul\CMS\Repositories\PageRepository;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Page
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository) {}

    /**
     * After page update
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function afterUpdate($page)
    {
        LSCache::purgeTags(['page_'.$page->id]);
    }

    /**
     * Before page delete
     *
     * @param  int  $pageId
     * @return void
     */
    public function beforeDelete($pageId)
    {
        $page = $this->pageRepository->find($pageId);

        LSCache::purgeTags(['page_'.$page->id]);
    }
}
