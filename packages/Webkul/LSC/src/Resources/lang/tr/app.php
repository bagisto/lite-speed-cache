<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Geliştirilmiş performans için LSCache ayarlarını yapılandırın.',
                'title' => 'LiteSpeed Önbellek',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache uygulamasını ve ilgili ayarları yönetin.',
                    'title' => 'Yapılandırma',

                    'cache-application' => [
                        'default-ttl-info' => 'Önbelleğe alınan öğelerin varsayılan yaşam süresini saniye cinsinden belirleyin <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Daha Fazla Bilgi</a>.',
                        'default-ttl'      => 'Varsayılan TTL (Time To Live)',
                        'guest-only-info'  => 'Önbellekleme yalnızca misafir kullanıcılar için etkinleştirilsin. Devre dışı bırakılırsa, önbellekleme tüm kullanıcılar için uygulanır.',
                        'guest-only'       => 'Yalnızca Misafirler',
                        'info'             => 'Önbellek uygulaması seçeneklerini belirleyin.',
                        'status'           => 'Durum',
                        'title-info'       => 'LiteSpeed Cache\'i yapılandırın: önbelleği etkinleştirin/devre dışı bırakın, varsayılan TTL\'yi ayarlayın ve yalnızca misafirler için önbellekleme seçin.',
                        'title'            => 'Önbellek Uygulaması',
                    ],
                ],
            ],
        ],
    ],
];
