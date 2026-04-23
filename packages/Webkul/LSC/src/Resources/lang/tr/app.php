<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed Önbellek',
        ],

        'acl' => [
            'title' => 'LiteSpeed Önbellek',
            'view'  => 'Görüntüle',
            'purge' => 'Temizle',
        ],

        'page' => [
            'title' => 'LiteSpeed Önbellek',
            'info'  => 'Önbellek etiketlerini inceleyin ve üretim ortamında sorun giderme ve bakım için hedefli temizlemeler gerçekleştirin.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Hızlı İşlemler',
                'info'  => 'Ana sayfa için hızlı temizleme işlemlerini kullanın veya gerekirse tüm önbelleği temizleyin.',
            ],

            'debug' => [
                'title'         => 'Hata Ayıklama Başlıkları',
                'tag-label'     => 'Etiket:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Politika:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Temizleme:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Kategoriye Göre Temizle',
                'info'  => 'Bir kategori arayın ve yalnızca ID tabanlı etiketini temizleyin.',
            ],

            'product' => [
                'title' => 'Ürüne Göre Temizle',
                'info'  => 'Bir ürün arayın ve yalnızca ID tabanlı etiketini temizleyin.',
            ],

            'tag' => [
                'title' => 'Etikete Göre Temizle',
                'info'  => 'Virgül veya boşlukla ayrılmış bir veya daha fazla LiteSpeed etiketi girin.',
            ],

            'url' => [
                'title' => 'URL\'ye Göre Temizle',
                'info'  => 'Göreli veya mutlak bir storefront URL için tam URL yolu temizleme isteği gönderin.',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Tüm Önbelleği Temizle',
            'purge-home'     => 'Ana Sayfayı Temizle',
            'purge-category' => 'Kategoriyi Temizle',
            'purge-product'  => 'Ürünü Temizle',
            'purge-tag'      => 'Etiketi Temizle',
            'purge-url'      => 'URL\'yi Temizle',
        ],

        'badges' => [
            'id-tag'     => 'ID Etiketi',
            'manual'     => 'Manuel',
            'exact-path' => 'Tam Yol',
        ],

        'fields' => [
            'category' => 'Kategori',
            'product'  => 'Ürün',
            'tags'     => 'Etiketler',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Hedef etiket:',
        ],

        'placeholders' => [
            'category' => 'Kategoriyi ad, slug veya ID ile ara',
            'product'  => 'Ürünü ada göre ara',
            'tags'     => 'Örnek: category_5 product_22 home',
            'url'      => 'Örnek: /footwears veya https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Göndermeden önce doğru kategoriyi seçin.',
            'product'  => 'Göndermeden önce doğru ürünü seçin.',
            'tags'     => 'Joker (*) burada engellenmiştir. Bunun yerine “Tüm Önbelleği Temizle” kullanın.',
            'url'      => 'İstek, LiteSpeed\'e gönderilmeden önce storefront yoluna normalize edilir.',
        ],

        'confirm' => [
            'all'      => 'Tüm LiteSpeed önbelleğini temizlemek istiyor musunuz? Bu yıkıcı bir işlemdir.',
            'home'     => 'Ana sayfa önbellek etiketlerini temizlemek istiyor musunuz?',
            'category' => 'Seçilen kategorinin önbellek etiketini temizlemek istiyor musunuz?',
            'product'  => 'Seçilen ürünün önbellek etiketini temizlemek istiyor musunuz?',
            'tag'      => 'Verilen LiteSpeed etiket listesini temizlemek istiyor musunuz?',
            'url'      => 'Verilen URL yolunu temizlemek istiyor musunuz?',
        ],

        'flash' => [
            'purge-all' => 'LiteSpeed önbelleğinin tamamını temizleme isteği gönderildi.',
            'home'      => 'Ana sayfa temizleme isteği gönderildi.',
            'category'  => ':name kategorisi için temizleme isteği gönderildi.',
            'product'   => ':name ürünü için temizleme isteği gönderildi.',
            'tags'      => 'Etiket temizleme isteği gönderildi.',
            'url'       => ':url için URL temizleme isteği gönderildi.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Performansı artırmak için LSCache ayarlarını yapılandırın.',
                'title' => 'LiteSpeed Önbellek',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache uygulamasını ve ilgili ayarları yönetin.',
                    'title' => 'Yapılandırma',

                    'cache-application' => [
                        'info'             => 'Önbellek uygulama seçeneklerini ayarlayın.',
                        'title'            => 'Önbellek Uygulaması',
                        'title-info'       => 'LiteSpeed Cache\'i yapılandırın: önbelleği etkinleştirin/devre dışı bırakın ve varsayılan TTL\'yi ayarlayın.',
                        'status'           => 'Durum',
                        'default-ttl'      => 'Varsayılan TTL (Time To Live)',
                        'default-ttl-info' => 'Önbellek öğeleri için varsayılan yaşam süresini saniye cinsinden ayarlayın <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Daha Fazla Bilgi</a>.',
                        'debug-mode'       => 'Hata Ayıklama Modu',
                        'debug-mode-info'  => 'Etkinleştirildiğinde, LiteSpeed etiketlerini, cache-control kararlarını ve istek bazlı temizleme işlemlerini gösteren hata ayıklama başlıkları ekler.',
                        'cache-path'       => 'Önbellek Yolu',
                        'cache-path-info'  => 'LiteSpeed özel önbellek dizininin tam yolu. Varsayılan: /usr/local/lsws/cachedata/priv (Enterprise) veya /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
