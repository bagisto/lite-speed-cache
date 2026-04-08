{{-- ESI Mini Cart Content --}}
{{-- This template is loaded via ESI and privately cached per user --}}

@if ($cart && $cart->items->count() > 0)
    <div class="lsc-mini-cart-content" data-cart-id="{{ $cart->id }}">
        {{-- Cart Items --}}
        <div class="mini-cart-items grid gap-4">
            @foreach ($cart->items as $item)
                <div class="mini-cart-item flex gap-4" data-item-id="{{ $item->id }}">
                    {{-- Item Image --}}
                    <div class="item-image">
                        <img
                            src="{{ $item->product?->base_image_url ?? '' }}"
                            alt="{{ $item->name }}"
                            class="w-16 h-16 rounded object-cover"
                            loading="lazy"
                        />
                    </div>

                    {{-- Item Details --}}
                    <div class="item-details flex-1">
                        <p class="item-name text-sm font-medium">{{ $item->name }}</p>
                        <p class="item-qty text-xs text-gray-500">
                            @lang('shop::app.checkout.cart.mini-cart.qty'): {{ $item->quantity }}
                        </p>
                        <p class="item-price text-sm font-semibold">
                            {{ core()->formatPrice($item->price) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Cart Summary --}}
        <div class="mini-cart-summary mt-4 pt-4 border-t">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm">@lang('shop::app.checkout.cart.mini-cart.subtotal')</span>
                <span class="font-semibold">{{ core()->formatPrice($cart->sub_total) }}</span>
            </div>

            @if ($cart->discount_amount > 0)
                <div class="flex justify-between items-center mb-2 text-green-600">
                    <span class="text-sm">@lang('shop::app.checkout.cart.mini-cart.discount')</span>
                    <span>-{{ core()->formatPrice($cart->discount_amount) }}</span>
                </div>
            @endif
        </div>

        {{-- Cart Actions --}}
        <div class="mini-cart-actions mt-4 grid gap-2">
            <a
                href="{{ route('shop.checkout.cart.index') }}"
                class="secondary-button w-full text-center py-2 rounded"
            >
                @lang('shop::app.checkout.cart.mini-cart.view-cart')
            </a>

            <a
                href="{{ route('shop.checkout.onepage.index') }}"
                class="primary-button w-full text-center py-2 rounded"
            >
                @lang('shop::app.checkout.cart.mini-cart.checkout')
            </a>
        </div>
    </div>
@else
    <div class="lsc-mini-cart-empty text-center py-8">
        <span class="icon-cart text-5xl text-gray-300"></span>
        <p class="mt-4 text-gray-500">@lang('shop::app.checkout.cart.mini-cart.empty-cart')</p>
        
        <a
            href="{{ route('shop.home.index') }}"
            class="primary-button inline-block mt-4 px-6 py-2 rounded"
        >
            @lang('shop::app.checkout.cart.mini-cart.continue-shopping')
        </a>
    </div>
@endif
