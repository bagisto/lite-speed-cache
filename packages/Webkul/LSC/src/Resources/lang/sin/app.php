<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed කැෂ්',
        ],

        'acl' => [
            'title' => 'LiteSpeed කැෂ්',
            'view'  => 'නරඹන්න',
            'purge' => 'පිරිසිදු කරන්න',
        ],

        'page' => [
            'title' => 'LiteSpeed කැෂ්',
            'info'  => 'කැෂ් ටැග් සමාලෝචනය කර නිෂ්පාදන පරිසරයේ දෝෂ විසඳීම සහ නඩත්තු කිරීම සඳහා ඉලක්කගත පිරිසිදු කිරීම් සිදු කරන්න.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'ඉක්මන් ක්‍රියා',
                'info'  => 'මුල් පිටුව සඳහා ඉක්මන් පිරිසිදු කිරීම් භාවිතා කරන්න, හෝ අවශ්‍ය නම් සම්පූර්ණ කැෂ් එකම පිරිසිදු කරන්න.',
            ],

            'debug' => [
                'title'         => 'ඩිබග් හෙඩර්',
                'tag-label'     => 'ටැග්:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'ප්‍රතිපත්තිය:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'පිරිසිදු කිරීම:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'කාණ්ඩ අනුව පිරිසිදු කරන්න',
                'info'  => 'කාණ්ඩයක් සොයන්න සහ එහි ID මත පදනම් වූ ටැග් එක පමණක් පිරිසිදු කරන්න.',
            ],

            'product' => [
                'title' => 'නිෂ්පාදන අනුව පිරිසිදු කරන්න',
                'info'  => 'නිෂ්පාදනයක් සොයන්න සහ එහි ID මත පදනම් වූ ටැග් එක පමණක් පිරිසිදු කරන්න.',
            ],

            'tag' => [
                'title' => 'ටැග් අනුව පිරිසිදු කරන්න',
                'info'  => 'කොමා හෝ අවකාශ වලින් වෙන්කර LiteSpeed ටැග් එකක් හෝ කිහිපයක් ඇතුළත් කරන්න.',
            ],

            'url' => [
                'title' => 'URL අනුව පිරිසිදු කරන්න',
                'info'  => 'නිවැරදි URL මාර්ගයක් සඳහා (relative හෝ absolute) පිරිසිදු කිරීමේ ඉල්ලීමක් යවන්න.',
            ],
        ],

        'actions' => [
            'purge-all'      => 'සම්පූර්ණ කැෂ් පිරිසිදු කරන්න',
            'purge-home'     => 'මුල් පිටුව පිරිසිදු කරන්න',
            'purge-category' => 'කාණ්ඩය පිරිසිදු කරන්න',
            'purge-product'  => 'නිෂ්පාදනය පිරිසිදු කරන්න',
            'purge-tag'      => 'ටැග් පිරිසිදු කරන්න',
            'purge-url'      => 'URL පිරිසිදු කරන්න',
        ],

        'badges' => [
            'id-tag'     => 'ID ටැග්',
            'manual'     => 'අතින්',
            'exact-path' => 'නිවැරදි මාර්ගය',
        ],

        'fields' => [
            'category' => 'කාණ්ඩය',
            'product'  => 'නිෂ්පාදනය',
            'tags'     => 'ටැග්',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'ඉලක්ක ටැග්:',
        ],

        'placeholders' => [
            'category' => 'නාමය, slug හෝ ID මගින් කාණ්ඩ සොයන්න',
            'product'  => 'නාමය මගින් නිෂ්පාදන සොයන්න',
            'tags'     => 'උදාහරණ: category_5 product_22 home',
            'url'      => 'උදාහරණ: /footwears හෝ https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'යැවීමට පෙර නිවැරදි කාණ්ඩයක් තෝරන්න.',
            'product'  => 'යැවීමට පෙර නිවැරදි නිෂ්පාදනයක් තෝරන්න.',
            'tags'     => 'Wildcard (*) මෙහි අවහිර කර ඇත. ඒ වෙනුවට “සම්පූර්ණ කැෂ් පිරිසිදු කරන්න” භාවිතා කරන්න.',
            'url'      => 'LiteSpeed වෙත යැවීමට පෙර ඉල්ලීම storefront මාර්ගයකට සාමාන්‍යකරණය කරනු ලැබේ.',
        ],

        'confirm' => [
            'all'      => 'සම්පූර්ණ LiteSpeed කැෂ් පිරිසිදු කිරීමට ඔබට අවශ්‍යද? මෙය විනාශකාරී ක්‍රියාවකි.',
            'home'     => 'මුල් පිටුවේ කැෂ් ටැග් පිරිසිදු කිරීමට අවශ්‍යද?',
            'category' => 'තෝරාගත් කාණ්ඩයේ කැෂ් ටැග් පිරිසිදු කිරීමට අවශ්‍යද?',
            'product'  => 'තෝරාගත් නිෂ්පාදනයේ කැෂ් ටැග් පිරිසිදු කිරීමට අවශ්‍යද?',
            'tag'      => 'සපයන ලද LiteSpeed ටැග් ලැයිස්තුව පිරිසිදු කිරීමට අවශ්‍යද?',
            'url'      => 'සපයන ලද URL මාර්ගය පිරිසිදු කිරීමට අවශ්‍යද?',
        ],

        'flash' => [
            'purge-all' => 'LiteSpeed කැෂ් සම්පූර්ණයෙන් පිරිසිදු කිරීමේ ඉල්ලීම යවා ඇත.',
            'home'      => 'මුල් පිටුව පිරිසිදු කිරීමේ ඉල්ලීම යවා ඇත.',
            'category'  => 'කාණ්ඩය :name සඳහා පිරිසිදු කිරීමේ ඉල්ලීම යවා ඇත.',
            'product'   => 'නිෂ්පාදනය :name සඳහා පිරිසිදු කිරීමේ ඉල්ලීම යවා ඇත.',
            'tags'      => 'ටැග් පිරිසිදු කිරීමේ ඉල්ලීම යවා ඇත.',
            'url'       => 'URL :url සඳහා පිරිසිදු කිරීමේ ඉල්ලීම යවා ඇත.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'කාර්ය සාධනය වැඩිදියුණු කිරීම සඳහා LSCache සැකසුම් වින්‍යාස කරන්න.',
                'title' => 'LiteSpeed කැෂ්',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache යෙදුම සහ අදාළ සැකසුම් කළමනාකරණය කරන්න.',
                    'title' => 'වින්‍යාසය',

                    'cache-application' => [
                        'info'             => 'කැෂ් යෙදුම් විකල්ප සකසන්න.',
                        'title'            => 'කැෂ් යෙදුම',
                        'title-info'       => 'LiteSpeed Cache වින්‍යාස කරන්න: කැෂ් සක්‍රීය/අක්‍රීය කිරීම සහ පෙරනිමි TTL සකසන්න.',
                        'status'           => 'තත්ත්වය',
                        'default-ttl'      => 'පෙරනිමි TTL (Time To Live)',
                        'default-ttl-info' => 'කැෂ් අයිතම සඳහා පෙරනිමි කාලය (තත්පර වලින්) සකසන්න <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">වැඩි විස්තර</a>.',
                        'debug-mode'       => 'ඩිබග් මාදිලිය',
                        'debug-mode-info'  => 'සක්‍රීය කළ විට, LiteSpeed ටැග්, cache-control තීරණ සහ පිරිසිදු කිරීමේ ක්‍රියා පෙන්වන ඩිබග් හෙඩර් එකතු කරයි.',
                        'cache-path'       => 'කැෂ් මාර්ගය',
                        'cache-path-info'  => 'LiteSpeed පෞද්ගලික කැෂ් ඩිරෙක්ටරියට සම්පූර්ණ මාර්ගය. පෙරනිමි: /usr/local/lsws/cachedata/priv (Enterprise) හෝ /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
