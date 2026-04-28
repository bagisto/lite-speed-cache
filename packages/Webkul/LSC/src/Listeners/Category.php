<?php

namespace Webkul\LSC\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Category
{
    /**
     * Track pre-update slugs so old category URLs/tags can be purged too.
     *
     * @var array<int, array<int, string>>
     */
    private static array $oldSlugsByCategoryId = [];

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected CategoryRepository $categoryRepository) {}

    /**
     * Capture the category slugs before update so old cached URLs can be purged.
     */
    public function beforeUpdate($categoryId): void
    {
        $category = $this->categoryRepository->find($categoryId);

        if (! $category) {
            return;
        }

        self::$oldSlugsByCategoryId[$categoryId] = $this->getCategorySlugs($category);
    }

    /**
     * After category update
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        try {
            $oldSlugs = self::$oldSlugsByCategoryId[$category->id] ?? [];

            $tags = $this->getCategoryTags($category, $oldSlugs);

            if (! empty($tags)) {
                LSCache::purgeTags($tags, false);
            }

            unset(self::$oldSlugsByCategoryId[$category->id]);
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache after category update', [
                'category_id' => $category->id ?? null,
                'error'       => $e->getMessage(),
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

            $tags = $this->getCategoryTags($category);

            if (! empty($tags)) {
                LSCache::purgeTags($tags, false);
            }
        } catch (\Throwable $e) {
            Log::error('LSCache: Failed to purge cache before category deletion', [
                'category_id' => $categoryId,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    /**
     * Resolve all LiteSpeed tags that can contain category-specific content.
     */
    private function getCategoryTags($category, array $extraSlugs = []): array
    {
        $tags = [
            'category_'.$category->id,
            'home-categories',
            'home-header',
        ];

        foreach (array_unique(array_merge($this->getCategorySlugs($category), $extraSlugs)) as $slug) {
            if (! empty($slug)) {
                $tags[] = 'category_'.$category->id;
            }
        }

        return array_values(array_unique(array_filter($tags)));
    }

    /**
     * Collect all known slugs for a category across locales.
     */
    private function getCategorySlugs($category): array
    {
        $slugs = [];

        foreach (core()->getAllLocales() as $locale) {
            $categoryTranslation = $category->translate($locale->code);

            if ($categoryTranslation && ! empty($categoryTranslation->slug)) {
                $slugs[] = $categoryTranslation->slug;
            }
        }

        $defaultTranslation = $category->translate(core()->getDefaultLocaleCodeFromDefaultChannel());

        if (
            $defaultTranslation
            && ! empty($defaultTranslation->slug)
        ) {
            $slugs[] = $defaultTranslation->slug;
        }

        return array_values(array_unique(array_filter($slugs)));
    }
}
