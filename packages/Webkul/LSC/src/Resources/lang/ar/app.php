<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'قم بتكوين إعدادات LSCache لتحسين الأداء.',
                'title' => 'ذاكرة التخزين المؤقت LiteSpeed',

                'configuration' => [
                    'info'  => 'إدارة تطبيق LiteSpeed Cache والإعدادات ذات الصلة.',
                    'title' => 'التكوين',

                    'cache-application' => [
                        'info'             => 'تعيين خيارات تطبيق التخزين المؤقت.',
                        'title'            => 'تطبيق التخزين المؤقت',
                        'title-info'       => 'قم بتكوين ذاكرة التخزين المؤقت LiteSpeed: تفعيل/تعطيل التخزين المؤقت، ضبط مدة الحياة الافتراضية (TTL)، واختيار التخزين المؤقت للضيوف فقط.',
                        'status'           => 'الحالة',
                        'default-ttl'      => 'المدة الافتراضية (TTL)',
                        'default-ttl-info' => 'اضبط المدة الافتراضية لبقاء العناصر المخزنة مؤقتًا بالثواني <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">مزيد من المعلومات</a>.',
                        'guest-only'       => 'للضيوف فقط',
                        'guest-only-info'  => 'قم بتمكين التخزين المؤقت للمستخدمين الضيوف فقط. إذا تم تعطيله، سيتم تطبيق التخزين المؤقت على جميع المستخدمين.',
                    ],
                ],
            ],
        ],
    ],
];
