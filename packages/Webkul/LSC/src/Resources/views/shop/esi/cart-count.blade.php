{{-- ESI Cart Count Badge --}}
{{-- This template is loaded via ESI and privately cached per user --}}
{{-- Returns just the cart count for the header badge --}}

@php
    $itemsCount = $cart?->items_count ?? 0;
    $itemsQty = $cart?->items_qty ?? 0;
    $displayMode = core()->getConfigData('sales.checkout.my_cart.summary') ?? 'display_item_quantity';
@endphp

@if ($itemsCount > 0 || $itemsQty > 0)
    @if ($displayMode === 'display_item_quantity')
        <span
            class="lsc-cart-badge absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5"
            data-cart-qty="{{ $itemsQty }}"
        >
            {{ $itemsQty }}
        </span>
    @else
        <span
            class="lsc-cart-badge absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5"
            data-cart-count="{{ $itemsCount }}"
        >
            {{ $itemsCount }}
        </span>
    @endif
@endif
