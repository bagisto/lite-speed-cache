<x-admin::layouts>
    <x-slot:title>
        @lang('lsc::app.admin.page.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('lsc::app.admin.page.title')
            </p>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                @lang('lsc::app.admin.page.info')
            </p>
        </div>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('settings.litespeed_cache.purge'))
                <x-admin::form
                    :action="route('admin.settings.lsc.purge.home')"
                >
                    <button
                        type="submit"
                        class="secondary-button"
                    >
                        @lang('lsc::app.admin.actions.purge-home')
                    </button>
                </x-admin::form>

                <x-admin::form
                    :action="route('admin.settings.lsc.purge.all')"
                >
                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('lsc::app.admin.actions.purge-all')
                    </button>
                </x-admin::form>
            @endif
        </div>
    </div>
    
    <v-lsc-cache-manager></v-lsc-cache-manager>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-lsc-cache-manager-template"
        >
            <div class="mt-6 grid gap-y-8">
                <div>
                    <div class="box-shadow mt-3 grid grid-cols-3 gap-4 rounded bg-white p-4 dark:bg-gray-900">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                                @lang('lsc::app.admin.cards.quick.title')
                            </p>

                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                @lang('lsc::app.admin.cards.quick.info')
                            </p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                                @lang('lsc::app.admin.cards.category.title')
                            </p>

                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                @lang('lsc::app.admin.cards.category.info')
                            </p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                                @lang('lsc::app.admin.cards.debug.title')
                            </p>

                            <div class="mt-2 grid gap-1 text-sm text-gray-600 dark:text-gray-300">
                                <p>
                                    <span class="font-semibold text-gray-800 dark:text-white">@lang('lsc::app.admin.cards.debug.tag-label')</span>
                                    <code> @lang('lsc::app.admin.cards.debug.tag-header')</code>
                                </p>

                                <p>
                                    <span class="font-semibold text-gray-800 dark:text-white">@lang('lsc::app.admin.cards.debug.policy-label')</span>
                                    <code> @lang('lsc::app.admin.cards.debug.policy-header')</code>
                                </p>

                                <p>
                                    <span class="font-semibold text-gray-800 dark:text-white">@lang('lsc::app.admin.cards.debug.purge-label')</span>
                                    <code> @lang('lsc::app.admin.cards.debug.purge-header')</code>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 xl:grid-cols-2">
                    <!-- Purge by category -->
                    <section class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('lsc::app.admin.cards.category.title')
                                </p>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    @lang('lsc::app.admin.cards.category.info')
                                </p>
                            </div>

                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                @lang('lsc::app.admin.badges.id-tag')
                            </span>
                        </div>

                        <x-admin::form
                            :action="route('admin.settings.lsc.purge.category')"
                            class="grid gap-4"
                        >
                            <x-admin::form.control-group class="relative z-20">
                                <x-admin::form.control-group.label>
                                    @lang('lsc::app.admin.fields.category')
                                </x-admin::form.control-group.label>

                                <input
                                    type="text"
                                    class="peer block w-full rounded-lg border bg-white px-4 py-2.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-300 dark:hover:border-gray-700 dark:focus:border-gray-700"
                                    :placeholder="'@lang('lsc::app.admin.placeholders.category')'"
                                    rules="required"
                                    v-model="categorySearch"
                                    @input="onCategoryInput"
                                    @focus="showCategoryDropdown = categoryOptions.length > 0"
                                >

                                <input
                                    type="hidden"
                                    name="category_id"
                                    :value="selectedCategoryId"
                                >

                                <div
                                    v-if="showCategoryDropdown && categoryOptions.length"
                                    class="absolute z-20 mt-2 w-full overflow-hidden rounded-lg border bg-white shadow-[0px_10px_30px_rgba(0,0,0,0.08)] dark:border-gray-800 dark:bg-gray-900"
                                >
                                    <button
                                        v-for="option in categoryOptions"
                                        :key="option.id"
                                        type="button"
                                        class="flex w-full items-center justify-between gap-3 border-b px-4 py-3 text-left text-sm text-gray-600 transition-all last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                        @mousedown.prevent="selectCategory(option)"
                                    >
                                        <span class="truncate">@{{ option.label }}</span>
                                        <span class="text-xs font-semibold text-gray-400">#@{{ option.id }}</span>
                                    </button>
                                </div>

                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    @lang('lsc::app.admin.hints.category')
                                </p>
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-3">
                                <button
                                    type="submit"
                                    class="secondary-button"
                                    :disabled="! selectedCategoryId"
                                >
                                    @lang('lsc::app.admin.actions.purge-category')
                                </button>

                                <p
                                    v-if="selectedCategoryId"
                                    class="text-xs font-medium text-emerald-600 dark:text-emerald-400"
                                >
                                    @lang('lsc::app.admin.labels.target-tag') <code>@{{ 'category_' + selectedCategoryId }}</code>
                                </p>
                            </div>
                        </x-admin::form>
                    </section>

                    <!-- Purge by Product -->
                    <section class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('lsc::app.admin.cards.product.title')
                                </p>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    @lang('lsc::app.admin.cards.product.info')
                                </p>
                            </div>

                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                @lang('lsc::app.admin.badges.id-tag')
                            </span>
                        </div>

                        <x-admin::form
                            :action="route('admin.settings.lsc.purge.product')"
                            class="grid gap-4"
                        >
                            <x-admin::form.control-group class="relative z-10">
                                <x-admin::form.control-group.label>
                                    @lang('lsc::app.admin.fields.product')
                                </x-admin::form.control-group.label>

                                <input
                                    type="text"
                                    class="peer block w-full rounded-lg border bg-white px-4 py-2.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-300 dark:hover:border-gray-700 dark:focus:border-gray-700"
                                    :placeholder="'@lang('lsc::app.admin.placeholders.product')'"
                                    v-model="productSearch"
                                    @input="onProductInput"
                                    @focus="showProductDropdown = productOptions.length > 0"
                                >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    :value="selectedProductId"
                                >

                                <div
                                    v-if="showProductDropdown && productOptions.length"
                                    class="absolute z-20 mt-2 w-full overflow-hidden rounded-lg border bg-white shadow-[0px_10px_30px_rgba(0,0,0,0.08)] dark:border-gray-800 dark:bg-gray-900"
                                >
                                    <button
                                        v-for="option in productOptions"
                                        :key="option.id"
                                        type="button"
                                        class="flex w-full items-center justify-between gap-3 border-b px-4 py-3 text-left text-sm text-gray-600 transition-all last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                        @mousedown.prevent="selectProduct(option)"
                                    >
                                        <span class="truncate">@{{ option.label }}</span>
                                        <span class="text-xs font-semibold text-gray-400">#@{{ option.id }}</span>
                                    </button>
                                </div>

                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    @lang('lsc::app.admin.hints.product')
                                </p>
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-3">
                                <button
                                    type="submit"
                                    class="secondary-button"
                                    :disabled="! selectedProductId"
                                >
                                    @lang('lsc::app.admin.actions.purge-product')
                                </button>

                                <p
                                    v-if="selectedProductId"
                                    class="text-xs font-medium text-emerald-600 dark:text-emerald-400"
                                >
                                    @lang('lsc::app.admin.labels.target-tag') <code>@{{ 'product_' + selectedProductId }}</code>
                                </p>
                            </div>
                        </x-admin::form>
                    </section>

                    <!-- Purge by Tag -->
                    <section class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('lsc::app.admin.cards.tag.title')
                                </p>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    @lang('lsc::app.admin.cards.tag.info')
                                </p>
                            </div>

                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                @lang('lsc::app.admin.badges.manual')
                            </span>
                        </div>

                        <x-admin::form
                            :action="route('admin.settings.lsc.purge.tag')"
                            class="grid gap-4"
                        >
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('lsc::app.admin.fields.tags')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="tags"
                                    rows="5"
                                    rules="required"
                                    :value="old('tags')"
                                    :label="trans('lsc::app.admin.fields.tags')"
                                    :placeholder="trans('lsc::app.admin.placeholders.tags')"
                                />

                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    @lang('lsc::app.admin.hints.tags')
                                </p>
                            </x-admin::form.control-group>
                            
                            <x-admin::form.control-group.error control-name="tags" />

                            <button
                                type="submit"
                                class="secondary-button"
                            >
                                @lang('lsc::app.admin.actions.purge-tag')
                            </button>
                        </x-admin::form>
                    </section>

                    <!-- Purge by URL -->
                    <section class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('lsc::app.admin.cards.url.title')
                                </p>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    @lang('lsc::app.admin.cards.url.info')
                                </p>
                            </div>

                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                @lang('lsc::app.admin.badges.exact-path')
                            </span>
                        </div>

                        <x-admin::form
                            :action="route('admin.settings.lsc.purge.url')"
                            class="grid gap-4"
                        >
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('lsc::app.admin.fields.url')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="url"
                                    rules="required"
                                    :value="old('url')"
                                    :label="trans('lsc::app.admin.fields.url')"
                                    :placeholder="trans('lsc::app.admin.placeholders.url')"
                                />
                                
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    @lang('lsc::app.admin.hints.url')
                                </p>
                                
                                <x-admin::form.control-group.error control-name="url" />
                            </x-admin::form.control-group>

                            <button
                                type="submit"
                                class="secondary-button"
                            >
                                @lang('lsc::app.admin.actions.purge-url')
                            </button>
                        </x-admin::form>
                    </section>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-lsc-cache-manager', {
                template: '#v-lsc-cache-manager-template',

                data() {
                    return {
                        categorySearch: '',
                        categoryOptions: [],
                        selectedCategoryId: '',
                        productSearch: '',
                        productOptions: [],
                        selectedProductId: '',
                        showCategoryDropdown: false,
                        showProductDropdown: false,
                    };
                },

                created() {
                    window.addEventListener('click', this.handleFocusOut);
                },

                beforeDestroy() {
                    window.removeEventListener('click', this.handleFocusOut);
                },

                methods: {
                    onCategoryInput() {
                        this.selectedCategoryId = '';
                        this.searchCategories();
                    },

                    searchCategories() {
                        if (this.categorySearch.length < 2) {
                            this.categoryOptions = [];
                            this.showCategoryDropdown = false;

                            return;
                        }

                        this.$axios.get('{{ route('admin.settings.lsc.search.categories') }}', {
                            params: {query: this.categorySearch},
                        }).then((response) => {
                            this.categoryOptions = response.data.data;
                            this.showCategoryDropdown = this.categoryOptions.length > 0;
                        });
                    },

                    onProductInput() {
                        this.selectedProductId = '';
                        this.searchProducts();
                    },

                    searchProducts() {
                        if (this.productSearch.length < 2) {
                            this.productOptions = [];
                            this.showProductDropdown = false;

                            return;
                        }

                        this.$axios.get('{{ route('admin.settings.lsc.search.products') }}', {
                            params: {query: this.productSearch},
                        }).then((response) => {
                            this.productOptions = response.data.data;
                            this.showProductDropdown = this.productOptions.length > 0;
                        });
                    },

                    selectCategory(option) {
                        this.categorySearch = option.label;
                        this.selectedCategoryId = option.id;
                        this.showCategoryDropdown = false;
                    },

                    selectProduct(option) {
                        this.productSearch = option.label;
                        this.selectedProductId = option.id;
                        this.showProductDropdown = false;
                    },

                    handleFocusOut(event) {
                        if (! this.$el.contains(event.target)) {
                            this.showCategoryDropdown = false;
                            this.showProductDropdown = false;
                        }
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
