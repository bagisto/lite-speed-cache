<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Konfigurasikan pengaturan LSCache untuk meningkatkan kinerja.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Kelola aplikasi LiteSpeed Cache dan pengaturan terkait.',
                    'title' => 'Konfigurasi',

                    'cache-application' => [
                        'default-ttl-info' => 'Atur waktu hidup (TTL) default untuk item yang di-cache dalam satuan detik <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Info Lebih Lanjut</a>.',
                        'default-ttl'      => 'TTL Default (Waktu Hidup)',
                        'guest-only-info'  => 'Aktifkan caching hanya untuk pengguna tamu. Jika dinonaktifkan, caching akan diterapkan untuk semua pengguna.',
                        'guest-only'       => 'Hanya Tamu',
                        'info'             => 'Atur opsi aplikasi cache.',
                        'status'           => 'Status',
                        'title-info'       => 'Konfigurasikan LiteSpeed Cache: aktifkan atau nonaktifkan caching, atur TTL default, dan pilih caching hanya untuk tamu.',
                        'title'            => 'Aplikasi Cache',
                    ],
                ],
            ],
        ],
    ],
];
