<script type="module">
    (() => {
        const patchKey = '__lscWishlistComponentPatchInstalled';
        const loginUrl = @json(route('shop.customer.session.index'));
        const wishlistUrl = @json(route('shop.api.customers.account.wishlist.store'));

        const resolveApp = () => window.app ?? (typeof app !== 'undefined' ? app : null);

        const resolveMessage = payload => payload?.data?.message
            ?? payload?.data?.data?.message
            ?? payload?.response?.data?.message
            ?? payload?.response?.data?.data?.message
            ?? null;

        const isLoginResponse = payload => {
            const response = payload?.response ?? payload;
            const status = response?.status ?? null;
            const finalUrl = response?.request?.responseURL ?? '';
            const contentType = String(response?.headers?.['content-type'] ?? '');

            return [401, 403].includes(status)
                || finalUrl.includes('/customer/login')
                || contentType.includes('text/html');
        };

        const redirectToLogin = payload => {
            if (! isLoginResponse(payload)) {
                return false;
            }

            const response = payload?.response ?? payload;
            const finalUrl = response?.request?.responseURL ?? loginUrl;

            window.location.href = finalUrl || loginUrl;

            return true;
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

            definition.methods.addToWishlist = function (...args) {
                const productId = resolveProductId(this, args);

                if (! productId) {
                    window.location.href = loginUrl;

                    return;
                }

                this.$axios.post(wishlistUrl, {
                    product_id: productId,
                }).then(response => {
                    if (redirectToLogin(response)) {
                        return;
                    }

                    if (Object.prototype.hasOwnProperty.call(this, 'product') && this.product) {
                        this.product.is_wishlist = ! this.product.is_wishlist;
                    }

                    if (Object.prototype.hasOwnProperty.call(this, 'isWishlist')) {
                        this.isWishlist = ! this.isWishlist;
                    }

                    if (Object.prototype.hasOwnProperty.call(this, 'isCustomer')) {
                        this.isCustomer = true;
                    }

                    const message = resolveMessage(response);

                    if (message) {
                        this.$emitter?.emit('add-flash', { type: 'success', message });
                    }
                }).catch(error => {
                    if (redirectToLogin(error)) {
                        return;
                    }

                    const message = resolveMessage(error);

                    if (message) {
                        this.$emitter?.emit('add-flash', { type: 'warning', message });
                    }
                });
            };

            return definition;
        };

        const installPatch = (attempt = 0) => {
            if (window[patchKey]) {
                return;
            }

            const vueApp = resolveApp();

            if (! vueApp || typeof vueApp.component !== 'function') {
                if (attempt < 50) {
                    window.setTimeout(() => installPatch(attempt + 1), 50);
                }

                return;
            }

            const originalComponent = vueApp.component.bind(vueApp);

            vueApp.component = (name, definition) => originalComponent(name, patchDefinition(name, definition));

            window[patchKey] = true;
        };

        installPatch();
    })();
</script>
