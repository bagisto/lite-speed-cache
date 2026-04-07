<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'הגדר את ההגדרות של LSCache כדי לשפר את הביצועים.',
                'title' => 'מטמון LiteSpeed',

                'configuration' => [
                    'info'  => 'נהל את יישום LiteSpeed Cache וההגדרות הקשורות.',
                    'title' => 'תצורה',

                    'cache-application' => [
                        'info'             => 'הגדר את אפשרויות יישום המטמון.',
                        'title'            => 'יישום המטמון',
                        'title-info'       => 'הגדר את LiteSpeed Cache: הפעל/השבת את המטמון, קבע את TTL ברירת המחדל ובחר האם המטמון יהיה רק עבור אורחים.',
                        'status'           => 'מצב',
                        'default-ttl'      => 'TTL ברירת מחדל (זמן חיים)',
                        'default-ttl-info' => 'הגדר את משך החיים ברירת מחדל של פריטים במטמון בשניות <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">מידע נוסף</a>.',
                        'guest-only'       => 'רק אורחים',
                        'guest-only-info'  => 'אפשר מטמון רק למשתמשים אורחים. אם מבוטל, המטמון יחול על כל המשתמשים.',
                    ],
                ],
            ],
        ],
    ],
];
