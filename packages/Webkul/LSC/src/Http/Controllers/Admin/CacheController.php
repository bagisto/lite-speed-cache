<?php

namespace Webkul\LSC\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\LSC\Support\LiteSpeedPurgeManager;
use Webkul\Product\Repositories\ProductRepository;

class CacheController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected LiteSpeedPurgeManager $purgeManager,
        protected ProductRepository $productRepository,
    ) {}

    /**
     * Display the cache management page.
     */
    public function index()
    {
        $this->authorizePermission('settings.litespeed_cache.view');

        return view('lsc::admin.cache.index');
    }

    /**
     * Search categories for the purge selector.
     */
    public function searchCategories(Request $request): JsonResponse
    {
        $this->authorizePermission('settings.litespeed_cache.view');

        $query = trim((string) $request->query('query', ''));

        $categories = $this->categoryRepository->getModel()
            ->query()
            ->select('categories.id', 'category_translations.name', 'category_translations.slug')
            ->join('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.locale', app()->getLocale())
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($builder) use ($query) {
                    $builder->where('category_translations.name', 'like', '%'.$query.'%')
                        ->orWhere('category_translations.slug', 'like', '%'.$query.'%')
                        ->orWhere('categories.id', 'like', '%'.$query.'%');
                });
            })
            ->orderBy('category_translations.name')
            ->limit(20)
            ->get()
            ->map(fn ($category) => [
                'id'    => $category->id,
                'label' => sprintf('%s (#%d) [%s]', $category->name, $category->id, $category->slug ?: '-'),
            ])
            ->values();

        return new JsonResponse(['data' => $categories]);
    }

    /**
     * Search products for the purge selector.
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $this->authorizePermission('settings.litespeed_cache.view');

        $query = trim((string) $request->query('query', ''));

        $params = [
            'query' => $query,
            'limit' => 20,
            'sort'  => 'created_at',
            'order' => 'desc',
        ];

        $products = $query === ''
            ? collect()
            : $this->productRepository->setSearchEngine(
                core()->getConfigData('catalog.products.search.engine') === 'elastic'
                && core()->getConfigData('catalog.products.search.admin_mode') === 'elastic'
                    ? 'elastic'
                    : 'database'
            )->getAll($params);

        $productItems = method_exists($products, 'items')
            ? $products->items()
            : $products;

        return new JsonResponse([
            'data' => collect($productItems)->map(fn ($product) => [
                'id'    => $product->id,
                'label' => sprintf('%s (#%d) [%s]', $product->name, $product->id, $product->url_key ?: $product->sku ?: '-'),
            ])->values(),
        ]);
    }

    /**
     * Purge all cache.
     */
    public function purgeAll(): RedirectResponse
    {
        $this->authorizePermission('settings.litespeed_cache.purge');

        $result = $this->purgeManager->purgeAll();

        return $this->redirectWithPurge('purge_all', $result);
    }

    /**
     * Purge the home page tags.
     */
    public function purgeHome(): RedirectResponse
    {
        $this->authorizePermission('settings.litespeed_cache.purge');

        $result = $this->purgeManager->purgeHome();

        return $this->redirectWithPurge('purge_home', $result);
    }

    /**
     * Purge a category tag.
     */
    public function purgeCategory(Request $request): RedirectResponse
    {
        $this->authorizePermission('settings.litespeed_cache.purge');

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $result = $this->purgeManager->purgeCategory((int) $validated['category_id']);

        return $this->redirectWithPurge('purge_category', $result);
    }

    /**
     * Purge a product tag.
     */
    public function purgeProduct(Request $request): RedirectResponse
    {
        $this->authorizePermission('settings.litespeed_cache.purge');

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $result = $this->purgeManager->purgeProduct((int) $validated['product_id']);

        return $this->redirectWithPurge('purge_product', $result);
    }

    /**
     * Purge one or more manual tags.
     */
    public function purgeTag(Request $request): RedirectResponse
    {
        $this->authorizePermission('settings.litespeed_cache.purge');

        $validated = $request->validate([
            'tags' => ['required', 'string', 'not_regex:/\*/'],
        ]);

        $tags = preg_split('/[\s,]+/', $validated['tags']) ?: [];
        $result = $this->purgeManager->purgeTags($tags);

        return $this->redirectWithPurge('purge_tags', $result);
    }

    /**
     * Purge a specific URL path.
     */
    public function purgeUrl(Request $request): RedirectResponse
    {
        $this->authorizePermission('settings.litespeed_cache.purge');

        $validated = $request->validate([
            'url' => 'required|string|max:2048',
        ]);

        $result = $this->purgeManager->purgeUrl($validated['url']);

        return $this->redirectWithPurge('purge_url', $result);
    }

    /**
     * Build a redirect response with flash messaging and purge headers.
     */
    protected function redirectWithPurge(string $action, array $result): RedirectResponse
    {
        $this->purgeManager->logAction($action, $result['log'] ?? []);

        session()->flash('success', $result['message']);

        $response = redirect()->route('admin.settings.lsc.index');
        $response->headers->set('X-LiteSpeed-Purge', $result['header']);

        return $response;
    }

    /**
     * Enforce ACL in controller actions.
     */
    protected function authorizePermission(string $permission): void
    {
        abort_unless(bouncer()->hasPermission($permission), 403);
    }
}
