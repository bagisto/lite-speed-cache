<script type="module">
    (() => {
        const patchKey = '__lscWishlistComponentPatchInstalled';
        const wishlistIndexUrl = @json(route('shop.api.customers.account.wishlist.index'));
        const isCustomerLoggedIn = @json(auth()->guard('customer')->check());
        let wishlistIdsPromise = null;

        const resolveApp = () => window.app ?? (typeof app !== 'undefined' ? app : null);
        const debug = (...args) => console.log('[LSC wishlist patch]', ...args);

        const extractWishlistIds = payload => {
            const items = payload?.data?.data ?? [];

            return new Set(
                items
                    .map(item => item?.product_id ?? item?.product?.id ?? null)
                    .filter(Boolean)
            );
        };

        const resolveWishlistIds = axios => {
            if (! isCustomerLoggedIn) {
                debug('skip wishlist sync for guest');

                return Promise.resolve(new Set());
            }

            if (! wishlistIdsPromise) {
                debug('fetching wishlist ids', wishlistIndexUrl);

                wishlistIdsPromise = axios.get(wishlistIndexUrl)
                    .then(response => {
                        if ([401, 403].includes(response?.status ?? null)) {
                            debug('wishlist sync auth failure response', response?.status ?? null);

                            return new Set();
                        }

                        const ids = extractWishlistIds(response);

                        debug('wishlist ids loaded', [...ids]);

                        return ids;
                    })
                    .catch(error => {
                        if ([401, 403].includes(error?.response?.status ?? null)) {
                            debug('wishlist sync auth failure error', error?.response?.status ?? null);

                            return new Set();
                        }

                        debug('wishlist sync failed', error);

                        return new Set();
                    });
            }

            return wishlistIdsPromise;
        };

        const syncWishlistState = component => {
            if (! isCustomerLoggedIn || ! component?.$axios) {
                debug('skip component sync', {
                    hasAxios: Boolean(component?.$axios),
                    isCustomerLoggedIn,
                });

                return;
            }

            if (Object.prototype.hasOwnProperty.call(component, 'isCustomer')) {
                component.isCustomer = true;
            }

            const productId = resolveProductId(component, []);

            if (! productId) {
                debug('skip sync, missing product id');

                return;
            }

            resolveWishlistIds(component.$axios).then(wishlistIds => {
                const isWishlisted = wishlistIds.has(productId);

                if (Object.prototype.hasOwnProperty.call(component, 'product') && component.product) {
                    component.product.is_wishlist = isWishlisted;
                }

                if (Object.prototype.hasOwnProperty.call(component, 'isWishlist')) {
                    component.isWishlist = isWishlisted;
                }

                if (Object.prototype.hasOwnProperty.call(component, 'isCustomer')) {
                    component.isCustomer = true;
                }

                debug('synced component state', {
                    productId,
                    isWishlisted,
                    component: component.$options?.name ?? component.$?.type?.name ?? 'unknown',
                });
            });
        };

        const resolveProductId = (component, args) => {
            if (component?.product?.id) {
                return component.product.id;
            }

            if (component?.$refs?.formData) {
                const formData = new FormData(component.$refs.formData);
                const productId = formData.get('product_id');

                if (productId) {
                    return productId;
                }
            }

            return args[0] ?? null;
        };

        const patchDefinition = (name, definition) => {
            if (! ['v-product', 'v-product-card'].includes(name) || ! definition?.methods?.addToWishlist) {
                return definition;
            }

            if (definition.__lscWishlistPatched) {
                debug('component already patched', name);

                return definition;
            }

            debug('patching component', name);

            const originalMounted = definition.mounted;

            definition.mounted = function (...args) {
                if (typeof originalMounted === 'function') {
                    originalMounted.apply(this, args);
                }

                syncWishlistState(this);
            };

            definition.__lscWishlistPatched = true;

            return definition;
        };

        const installPatch = (attempt = 0) => {
            if (window[patchKey]) {
                return;
            }

            const vueApp = resolveApp();

            if (! vueApp || typeof vueApp.component !== 'function') {
                if (attempt < 50) {
                    debug('waiting for vue app', attempt);

                    window.setTimeout(() => installPatch(attempt + 1), 50);
                }

                return;
            }

            const originalComponent = vueApp.component.bind(vueApp);

            vueApp.component = (name, definition) => originalComponent(name, patchDefinition(name, definition));

            ['v-product', 'v-product-card'].forEach(name => {
                const definition = originalComponent(name);

                if (! definition) {
                    debug('component not registered yet', name);

                    return;
                }

                originalComponent(name, patchDefinition(name, definition));
            });

            debug('wishlist patch installed');

            window[patchKey] = true;
        };

        installPatch();
    })();
</script>
