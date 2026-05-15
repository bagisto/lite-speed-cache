<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Cache LiteSpeed',
        ],

        'acl' => [
            'title' => 'Cache LiteSpeed',
            'view'  => 'Lihat',
            'purge' => 'Bersihkan',
        ],

        'page' => [
            'title' => 'Cache LiteSpeed',
            'info'  => 'Tinjau tag cache dan jalankan pembersihan terarah untuk troubleshooting dan pemeliharaan di lingkungan produksi.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Aksi Cepat',
                'info'  => 'Gunakan aksi pembersihan cepat untuk halaman utama atau, jika benar-benar diperlukan, untuk seluruh cache.',
            ],

            'debug' => [
                'title'         => 'Header Debug',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Kebijakan:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purge:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Bersihkan Berdasarkan Kategori',
                'info'  => 'Cari kategori dan bersihkan hanya tag berdasarkan ID-nya.',
            ],

            'product' => [
                'title' => 'Bersihkan Berdasarkan Produk',
                'info'  => 'Cari produk dan bersihkan hanya tag berdasarkan ID-nya.',
            ],

            'tag' => [
                'title' => 'Bersihkan Berdasarkan Tag',
                'info'  => 'Masukkan satu atau lebih tag LiteSpeed yang dipisahkan dengan koma atau spasi.',
            ],

            'url' => [
                'title' => 'Bersihkan Berdasarkan URL',
                'info'  => 'Kirim permintaan purge untuk path URL yang tepat (relatif atau absolut).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Bersihkan Semua Cache',
            'purge-home'     => 'Bersihkan Halaman Utama',
            'purge-category' => 'Bersihkan Kategori',
            'purge-product'  => 'Bersihkan Produk',
            'purge-tag'      => 'Bersihkan Tag',
            'purge-url'      => 'Bersihkan URL',
        ],

        'badges' => [
            'id-tag'     => 'Tag ID',
            'manual'     => 'Manual',
            'exact-path' => 'Path Tepat',
        ],

        'fields' => [
            'category' => 'Kategori',
            'product'  => 'Produk',
            'tags'     => 'Tag',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Tag target:',
        ],

        'placeholders' => [
            'category' => 'Cari kategori berdasarkan nama, slug, atau ID',
            'product'  => 'Cari produk berdasarkan nama',
            'tags'     => 'Contoh: category_5 product_22 home',
            'url'      => 'Contoh: /footwears atau https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Pilih kategori yang tepat sebelum mengirim.',
            'product'  => 'Pilih produk yang tepat sebelum mengirim.',
            'tags'     => 'Wildcard (*) diblokir di sini. Gunakan “Bersihkan Semua Cache” sebagai gantinya.',
            'url'      => 'Permintaan dinormalisasi ke path storefront sebelum dikirim ke LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Bersihkan seluruh cache LiteSpeed? Ini adalah tindakan destruktif.',
            'home'     => 'Bersihkan tag cache halaman utama?',
            'category' => 'Bersihkan tag cache kategori yang dipilih?',
            'product'  => 'Bersihkan tag cache produk yang dipilih?',
            'tag'      => 'Bersihkan daftar tag LiteSpeed yang diberikan?',
            'url'      => 'Bersihkan path URL yang diberikan?',
        ],

        'flash' => [
            'purge-all' => 'Permintaan pembersihan seluruh cache LiteSpeed telah dikirim.',
            'home'      => 'Permintaan pembersihan halaman utama telah dikirim.',
            'category'  => 'Permintaan pembersihan dikirim untuk kategori :name.',
            'product'   => 'Permintaan pembersihan dikirim untuk produk :name.',
            'tags'      => 'Permintaan pembersihan tag telah dikirim.',
            'url'       => 'Permintaan pembersihan URL dikirim untuk :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Konfigurasikan pengaturan LSCache untuk meningkatkan performa.',
                'title' => 'Cache LiteSpeed',

                'configuration' => [
                    'info'  => 'Kelola aplikasi LiteSpeed Cache dan pengaturan terkait.',
                    'title' => 'Konfigurasi',

                    'cache-application' => [
                        'info'             => 'Atur opsi aplikasi cache.',
                        'title'            => 'Aplikasi Cache',
                        'title-info'       => 'Konfigurasikan LiteSpeed Cache: aktifkan/nonaktifkan cache dan atur TTL default.',
                        'status'           => 'Status',
                        'default-ttl'      => 'TTL Default (Time To Live)',
                        'default-ttl-info' => 'Atur waktu hidup default untuk item cache dalam detik <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Info Lebih Lanjut</a>.',
                        'debug-mode'       => 'Mode Debug',
                        'debug-mode-info'  => 'Saat diaktifkan, menambahkan header debug yang menampilkan tag LiteSpeed, keputusan cache-control, dan operasi purge per permintaan.',
                        'cache-path'       => 'Path Cache',
                        'cache-path-info'  => 'Path lengkap ke direktori cache privat LiteSpeed. Default: /usr/local/lsws/cachedata/priv (Enterprise) atau /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
