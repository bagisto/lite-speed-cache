<?php

namespace Webkul\LSC\Listeners;

use LSCache;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\LSC\Traits\DeletesAllCache;

class Category
{
    use DeletesAllCache;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected CategoryRepository $categoryRepository) {}

    /**
     * After category update
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        foreach (core()->getAllLocales() as $locale) {
            if ($categoryTranslation = $category->translate($locale->code)) {
                LSCache::purgeTags(['category_'.$categoryTranslation->slug]);

                $this->deletePrivCache();
            }

            LSCache::purgeTags(['category_'.$category->translate(core()->getDefaultLocaleCodeFromDefaultChannel())->slug]);
        }
    }

    /**
     * Before category delete
     *
     * @param  int  $categoryId
     * @return void
     */
    public function beforeDelete($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        foreach (core()->getAllLocales() as $locale) {
            if ($categoryTranslation = $category->translate($locale->code)) {
                LSCache::purgeTags(['category_'.$categoryTranslation->slug]);

                $this->deletePrivCache();
            }

            LSCache::purgeTags(['category_'.$category->translate(core()->getDefaultLocaleCodeFromDefaultChannel())->slug]);
        }
    }
}
