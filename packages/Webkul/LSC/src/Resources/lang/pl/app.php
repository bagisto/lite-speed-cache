<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Pamięć podręczna LiteSpeed',
        ],

        'acl' => [
            'title' => 'Pamięć podręczna LiteSpeed',
            'view'  => 'Wyświetl',
            'purge' => 'Wyczyść',
        ],

        'page' => [
            'title' => 'Pamięć podręczna LiteSpeed',
            'info'  => 'Przeglądaj tagi pamięci podręcznej i uruchamiaj selektywne czyszczenie w celu rozwiązywania problemów i utrzymania w środowisku produkcyjnym.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Szybkie akcje',
                'info'  => 'Użyj szybkiego czyszczenia dla strony głównej lub, w razie potrzeby, dla całej pamięci podręcznej.',
            ],

            'debug' => [
                'title'         => 'Nagłówki debugowania',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Polityka:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Czyszczenie:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Wyczyść według kategorii',
                'info'  => 'Wyszukaj kategorię i wyczyść tylko tag oparty na jej ID.',
            ],

            'product' => [
                'title' => 'Wyczyść według produktu',
                'info'  => 'Wyszukaj produkt i wyczyść tylko tag oparty na jego ID.',
            ],

            'tag' => [
                'title' => 'Wyczyść według tagu',
                'info'  => 'Wprowadź jeden lub więcej tagów LiteSpeed oddzielonych przecinkami lub spacjami.',
            ],

            'url' => [
                'title' => 'Wyczyść według URL',
                'info'  => 'Wyślij żądanie czyszczenia dla dokładnej ścieżki URL (względnej lub absolutnej).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Wyczyść całą pamięć podręczną',
            'purge-home'     => 'Wyczyść stronę główną',
            'purge-category' => 'Wyczyść kategorię',
            'purge-product'  => 'Wyczyść produkt',
            'purge-tag'      => 'Wyczyść tag',
            'purge-url'      => 'Wyczyść URL',
        ],

        'badges' => [
            'id-tag'     => 'Tag ID',
            'manual'     => 'Ręcznie',
            'exact-path' => 'Dokładna ścieżka',
        ],

        'fields' => [
            'category' => 'Kategoria',
            'product'  => 'Produkt',
            'tags'     => 'Tagi',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Docelowy tag:',
        ],

        'placeholders' => [
            'category' => 'Wyszukaj kategorie po nazwie, slug lub ID',
            'product'  => 'Wyszukaj produkty po nazwie',
            'tags'     => 'Przykład: category_5 product_22 home',
            'url'      => 'Przykład: /footwears lub https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Wybierz dokładną kategorię przed wysłaniem.',
            'product'  => 'Wybierz dokładny produkt przed wysłaniem.',
            'tags'     => 'Wildcard (*) jest tutaj zablokowany. Zamiast tego użyj „Wyczyść całą pamięć podręczną”.',
            'url'      => 'Żądanie jest normalizowane do ścieżki storefront przed wysłaniem do LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Czy wyczyścić całą pamięć podręczną LiteSpeed? To działanie jest nieodwracalne.',
            'home'     => 'Czy wyczyścić tagi pamięci podręcznej strony głównej?',
            'category' => 'Czy wyczyścić tag pamięci podręcznej wybranej kategorii?',
            'product'  => 'Czy wyczyścić tag pamięci podręcznej wybranego produktu?',
            'tag'      => 'Czy wyczyścić podaną listę tagów LiteSpeed?',
            'url'      => 'Czy wyczyścić podaną ścieżkę URL?',
        ],

        'flash' => [
            'purge-all' => 'Wysłano żądanie wyczyszczenia całej pamięci podręcznej LiteSpeed.',
            'home'      => 'Wysłano żądanie wyczyszczenia strony głównej.',
            'category'  => 'Wysłano żądanie wyczyszczenia dla kategorii :name.',
            'product'   => 'Wysłano żądanie wyczyszczenia dla produktu :name.',
            'tags'      => 'Wysłano żądanie wyczyszczenia tagów.',
            'url'       => 'Wysłano żądanie wyczyszczenia URL dla :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Skonfiguruj ustawienia LSCache, aby poprawić wydajność.',
                'title' => 'Pamięć podręczna LiteSpeed',

                'configuration' => [
                    'info'  => 'Zarządzaj aplikacją LiteSpeed Cache i powiązanymi ustawieniami.',
                    'title' => 'Konfiguracja',

                    'cache-application' => [
                        'info'             => 'Ustaw opcje aplikacji pamięci podręcznej.',
                        'title'            => 'Aplikacja cache',
                        'title-info'       => 'Skonfiguruj LiteSpeed Cache: włącz/wyłącz cache i ustaw domyślny TTL.',
                        'status'           => 'Status',
                        'default-ttl'      => 'Domyślny TTL (Time To Live)',
                        'default-ttl-info' => 'Ustaw domyślny czas życia elementów cache w sekundach <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Więcej informacji</a>.',
                        'debug-mode'       => 'Tryb debugowania',
                        'debug-mode-info'  => 'Po włączeniu dodaje nagłówki debugowania pokazujące tagi LiteSpeed, decyzje cache-control oraz operacje czyszczenia dla żądań.',
                        'cache-path'       => 'Ścieżka cache',
                        'cache-path-info'  => 'Pełna ścieżka do prywatnego katalogu cache LiteSpeed. Domyślnie: /usr/local/lsws/cachedata/priv (Enterprise) lub /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
