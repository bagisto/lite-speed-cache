<?php

namespace Webkul\LSC\Http\Controllers\Admin\Catalog;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Catalog\CategoryController as BaseCategoryController;
use Webkul\Admin\Http\Requests\CategoryRequest;
use Webkul\LSC\Support\LiteSpeedDebug;

class CategoryController extends BaseCategoryController
{
    /**
     * Update the specified resource in storage and attach a LiteSpeed purge header
     * to the final response so the web server receives it on the redirect response.
     */
    public function update(CategoryRequest $categoryRequest, int $id): RedirectResponse
    {
        Event::dispatch('catalog.category.update.before', $id);

        $category = $this->categoryRepository->update($categoryRequest->only(
            'locale',
            'parent_id',
            'logo_path',
            'banner_path',
            'position',
            'display_mode',
            'status',
            'attributes',
            $categoryRequest->input('locale')
        ), $id);

        Event::dispatch('catalog.category.update.after', $category);

        session()->flash('success', trans('admin::app.catalog.categories.update-success'));

        $response = redirect()->route('admin.catalog.categories.index');
        $categoryTags = $this->getCategoryTags($category);
        $categoryUrls = $this->getCategoryUrls($category);

        $response->headers->set('X-LiteSpeed-Purge', $this->buildCategoryPurgeHeader($categoryTags, $categoryUrls));

        LiteSpeedDebug::recordPurgeTags($categoryTags);
        LiteSpeedDebug::recordPurgeUrls($categoryUrls);

        return $response;
    }

    /**
     * Build one purge header containing both tag and exact-url purges.
     */
    private function buildCategoryPurgeHeader(array $tags, array $urls): string
    {
        $parts = array_merge(
            array_map(
                fn ($tag) => 'tag='.$tag,
                $tags
            ),
            array_map(
                fn ($url) => 'url='.$url,
                $urls
            )
        );

        return 'public,'.implode(',', array_values(array_unique(array_filter($parts))));
    }

    /**
     * Resolve all relevant category tags.
     */
    private function getCategoryTags($category): array
    {
        return ['category_'.$category->id];
    }

    /**
     * Resolve exact category URLs for direct URL purging.
     */
    private function getCategoryUrls($category): array
    {
        $urls = [];

        foreach (core()->getAllLocales() as $locale) {
            $categoryTranslation = $category->translate($locale->code);

            if ($categoryTranslation && ! empty($categoryTranslation->slug)) {
                $urls[] = '/'.$categoryTranslation->slug;
            }
        }

        $defaultTranslation = $category->translate(core()->getDefaultLocaleCodeFromDefaultChannel());

        if ($defaultTranslation && ! empty($defaultTranslation->slug)) {
            $urls[] = '/'.$defaultTranslation->slug;
        }

        return array_values(array_unique(array_filter($urls)));
    }
}
