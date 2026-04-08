<?php

namespace Webkul\LSC\Listeners;

use Illuminate\Support\Facades\Log;
use Litespeed\LSCache\LSCache;
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
        try {
            $tags = [];

            foreach (core()->getAllLocales() as $locale) {
                $categoryTranslation = $category->translate($locale->code);
                
                if ($categoryTranslation && !empty($categoryTranslation->slug)) {
                    $tags[] = 'category_'.$categoryTranslation->slug;
                }
            }

            $defaultTranslation = $category->translate(core()->getDefaultLocaleCodeFromDefaultChannel());
            
            if (
                $defaultTranslation 
                && ! empty($defaultTranslation->slug)
            ) {
                $tags[] = 'category_'.$defaultTranslation->slug;
            }

            if (! empty($tags)) {
                LSCache::purgeTags(array_unique($tags));

                $this->deletePrivCache();
            }
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache after category update', [
                'category_id' => $category->id ?? null,
                'error' => $e->getMessage(),
            ]);
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
        try {
            $category = $this->categoryRepository->find($categoryId);

            if (! $category) {
                Log::warning('LSCache: Category not found for cache deletion', ['category_id' => $categoryId]);
                
                return;
            }

            $tags = [];

            foreach (core()->getAllLocales() as $locale) {
                $categoryTranslation = $category->translate($locale->code);
                
                if ($categoryTranslation && !empty($categoryTranslation->slug)) {
                    $tags[] = 'category_'.$categoryTranslation->slug;
                }
            }

            $defaultTranslation = $category->translate(core()->getDefaultLocaleCodeFromDefaultChannel());

            if (
                $defaultTranslation 
                && ! empty($defaultTranslation->slug)
            ) {
                $tags[] = 'category_'.$defaultTranslation->slug;
            }

            if (! empty($tags)) {
                LSCache::purgeTags(array_unique($tags));

                $this->deletePrivCache();
            }
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache before category deletion', [
                'category_id' => $categoryId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
