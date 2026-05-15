<?php

namespace Webkul\LSC\Http\Controllers\Admin\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Settings\ThemeController as BaseThemeController;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class ThemeController extends BaseThemeController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public ThemeCustomizationRepository $themeCustomizationRepository) {}

    /**
     * Mass update the specified resource in storage.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedThemeIds = $massUpdateRequest->input('indices');

        foreach ($selectedThemeIds as $themeId) {
            Event::dispatch('theme_customization.update.before', $themeId);

            $theme = $this->themeCustomizationRepository->find($themeId);

            $theme->status = $massUpdateRequest->input('value');

            $theme->save();

            Event::dispatch('theme_customization.update.after', $theme);
        }

        return new JsonResponse([
            'message' => trans('admin::app.settings.themes.update-success'),
        ]);
    }

    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $selectedThemeIds = $massDestroyRequest->input('indices');

        foreach ($selectedThemeIds as $themeId) {
            Event::dispatch('theme_customization.delete.before', $themeId);

            $this->themeCustomizationRepository->delete($themeId);

            Event::dispatch('theme_customization.delete.after', $themeId);
        }

        return new JsonResponse([
            'message' => trans('admin::app.settings.themes.update-success'),
        ]);
    }
}
