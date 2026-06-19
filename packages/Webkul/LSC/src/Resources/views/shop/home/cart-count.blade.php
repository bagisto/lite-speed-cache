@php
    $summary = core()->getConfigData('sales.checkout.my_cart.summary');

    $count = $summary === 'display_item_quantity' ? ($itemsQty ?? 0) : ($itemsCount ?? 0);
@endphp

@if ($count > 0)
    <span class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5 max-md:px-2 max-md:py-1.5 max-md:ltr:left-4 max-md:rtl:right-4">
        {{ $count }}
    </span>
@endif
