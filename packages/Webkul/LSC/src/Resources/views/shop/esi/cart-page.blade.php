{{-- ESI Cart Page Content --}}
{{-- This template is loaded via ESI for the cart page and privately cached per user --}}

@if ($cart && $cart->items->count() > 0)
    <div class="lsc-cart-page-content" data-cart-id="{{ $cart->id }}">
        {{-- Cart Items List --}}
        <div class="cart-items-container grid gap-6">
            @foreach ($cart->items as $item)
                <div class="cart-item flex gap-6 p-4 border rounded-lg" data-item-id="{{ $item->id }}">
                    {{-- Product Image --}}
                    <div class="item-image flex-shrink-0">
                        <a href="{{ route('shop.product_or_category.index', $item->product?->url_key ?? '') }}">
                            <img
                                src="{{ $item->product?->base_image_url ?? '' }}"
                                alt="{{ $item->name }}"
                                class="w-24 h-24 rounded object-cover"
                                loading="lazy"
                            />
                        </a>
                    </div>

                    {{-- Product Details --}}
                    <div class="item-details flex-1 grid gap-2">
                        <a
                            href="{{ route('shop.product_or_category.index', $item->product?->url_key ?? '') }}"
                            class="text-lg font-medium hover:text-blue-600"
                        >
                            {{ $item->name }}
                        </a>

                        {{-- Item Options --}}
                        @if ($item->additional && isset($item->additional['attributes']))
                            <div class="item-options text-sm text-gray-500">
                                @foreach ($item->additional['attributes'] as $attribute => $value)
                                    <span>{{ $attribute }}: {{ $value }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Price --}}
                        <div class="item-price">
                            <span class="text-lg font-semibold">
                                {{ core()->formatPrice($item->price) }}
                            </span>
                            @if ($item->quantity > 1)
                                <span class="text-sm text-gray-500">
                                    × {{ $item->quantity }} = {{ core()->formatPrice($item->total) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Quantity Controls (placeholder - actual controls handled by Vue) --}}
                    <div class="item-quantity flex items-center gap-2">
                        <span class="font-medium">{{ $item->quantity }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Cart Summary --}}
        <div class="cart-summary mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-xl font-semibold mb-4">@lang('shop::app.checkout.cart.cart-summary')</h3>

            <div class="summary-lines grid gap-3">
                {{-- Subtotal --}}
                <div class="flex justify-between">
                    <span>@lang('shop::app.checkout.cart.sub-total')</span>
                    <span class="font-medium">{{ core()->formatPrice($cart->sub_total) }}</span>
                </div>

                {{-- Discount --}}
                @if ($cart->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>@lang('shop::app.checkout.cart.discount')</span>
                        <span>-{{ core()->formatPrice($cart->discount_amount) }}</span>
                    </div>
                @endif

                {{-- Tax --}}
                @if ($cart->tax_total > 0)
                    <div class="flex justify-between">
                        <span>@lang('shop::app.checkout.cart.tax')</span>
                        <span>{{ core()->formatPrice($cart->tax_total) }}</span>
                    </div>
                @endif

                {{-- Grand Total --}}
                <div class="flex justify-between pt-3 border-t text-lg font-semibold">
                    <span>@lang('shop::app.checkout.cart.grand-total')</span>
                    <span>{{ core()->formatPrice($cart->grand_total) }}</span>
                </div>
            </div>

            {{-- Checkout Button --}}
            <a
                href="{{ route('shop.checkout.onepage.index') }}"
                class="primary-button w-full text-center py-3 mt-6 rounded-lg text-lg"
            >
                @lang('shop::app.checkout.cart.proceed-to-checkout')
            </a>
        </div>
    </div>
@else
    <div class="lsc-cart-empty text-center py-16">
        <span class="icon-cart text-8xl text-gray-300"></span>
        <h2 class="mt-6 text-2xl font-semibold text-gray-600">
            @lang('shop::app.checkout.cart.empty-cart')
        </h2>
        <p class="mt-2 text-gray-500">
            @lang('shop::app.checkout.cart.empty-cart-description')
        </p>

        <a
            href="{{ route('shop.home.index') }}"
            class="primary-button inline-block mt-6 px-8 py-3 rounded-lg"
        >
            @lang('shop::app.checkout.cart.continue-shopping')
        </a>
    </div>
@endif
