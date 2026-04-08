{{--
    ESI Cart Component with AJAX Fallback
    
    This component renders an ESI block when LiteSpeed ESI is enabled,
    otherwise falls back to AJAX loading for cart content.
    
    Usage:
        @include('lsc::shop.components.esi.cart', ['type' => 'mini-cart'])
        @include('lsc::shop.components.esi.cart', ['type' => 'cart-count'])
        @include('lsc::shop.components.esi.cart', ['type' => 'cart-data'])
--}}

@php
    $esiEnabled = (bool) config('lscache.esi', false) 
        || (bool) core()->getConfigData('lsc.configuration.esi_settings.enabled');
    
    $esiType = $type ?? 'mini-cart';
    $esiUrl = match ($esiType) {
        'mini-cart' => '/esi/mini-cart',
        'cart-count' => '/esi/cart-count',
        'cart-data' => '/esi/cart-data',
        'cart-page' => '/esi/cart-page',
        default => '/esi/mini-cart',
    };
    
    $fallbackId = 'lsc-esi-' . $esiType . '-' . uniqid();
@endphp

@if ($esiEnabled)
    {{-- ESI Block - LiteSpeed will process this and cache per user --}}
    <esi:include src="{{ $esiUrl }}" onerror="continue" />
    
    {{-- Fallback script in case ESI is not processed --}}
    <noscript>
        <div class="lsc-esi-noscript" data-esi-type="{{ $esiType }}">
            {{-- Static fallback content --}}
            @if ($esiType === 'cart-count')
                <span class="lsc-cart-count-placeholder"></span>
            @else
                <div class="lsc-loading">Loading...</div>
            @endif
        </div>
    </noscript>
@else
    {{-- AJAX Fallback when ESI is disabled --}}
    <div id="{{ $fallbackId }}" class="lsc-esi-fallback" data-esi-type="{{ $esiType }}">
        @if ($esiType === 'cart-count')
            <span class="lsc-cart-count-loading"></span>
        @else
            <div class="lsc-loading-placeholder">
                <span class="shimmer h-6 w-20 rounded" role="presentation"></span>
            </div>
        @endif
    </div>

    @pushOnce('scripts')
        <script>
            // LSC ESI Fallback Handler
            (function() {
                const loadEsiContent = function(container, type) {
                    const esiUrl = '/esi/' + type;
                    
                    fetch(esiUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('ESI fetch failed');
                        }
                        return response.text();
                    })
                    .then(html => {
                        container.innerHTML = html;
                    })
                    .catch(error => {
                        console.warn('LSC ESI fallback error:', error);
                        // Keep the placeholder or show empty state
                    });
                };

                // Load all ESI fallback containers
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.lsc-esi-fallback').forEach(function(container) {
                        const type = container.getAttribute('data-esi-type');
                        if (type) {
                            loadEsiContent(container, type);
                        }
                    });
                });

                // Also refresh cart content after cart operations
                window.refreshLscCartEsi = function() {
                    document.querySelectorAll('.lsc-esi-fallback[data-esi-type="mini-cart"]').forEach(function(container) {
                        loadEsiContent(container, 'mini-cart');
                    });
                    document.querySelectorAll('.lsc-esi-fallback[data-esi-type="cart-count"]').forEach(function(container) {
                        loadEsiContent(container, 'cart-count');
                    });
                };
            })();
        </script>
    @endpushOnce
@endif
