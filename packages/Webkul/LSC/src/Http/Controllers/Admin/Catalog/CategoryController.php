<?php

namespace Webkul\LSC\Http\Controllers\Admin\Catalog;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Catalog\CategoryController as BaseCategoryController;
use Webkul\Admin\Http\Requests\CategoryRequest;

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
        $response->headers->set('X-LiteSpeed-Purge', $this->buildCategoryPurgeHeader($category));

        return $response;
    }

    /**
     * Build one purge header containing both tag and exact-url purges.
     */
    private function buildCategoryPurgeHeader($category): string
    {
        $parts = array_merge(
            array_map(
                fn ($tag) => 'tag='.$tag,
                $this->getCategoryTags($category)
            ),
            array_map(
                fn ($url) => 'url='.$url,
                $this->getCategoryUrls($category)
            )
        );

        return 'public,'.implode(',', array_values(array_unique(array_filter($parts))));
    }

    /**
     * Resolve all relevant category tags.
     */
    private function getCategoryTags($category): array
    {
        $tags = [
            'categories',
            'categories-tree',
            'categories-max-price',
            'category_id_'.$category->id,
            'category-products_'.$category->id,
        ];

        foreach (core()->getAllLocales() as $locale) {
            $categoryTranslation = $category->translate($locale->code);

            if ($categoryTranslation && ! empty($categoryTranslation->slug)) {
                $tags[] = 'category_'.$categoryTranslation->slug;
            }
        }

        $defaultTranslation = $category->translate(core()->getDefaultLocaleCodeFromDefaultChannel());

        if ($defaultTranslation && ! empty($defaultTranslation->slug)) {
            $tags[] = 'category_'.$defaultTranslation->slug;
        }

        return array_values(array_unique(array_filter($tags)));
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
