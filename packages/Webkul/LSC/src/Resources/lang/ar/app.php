<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'ذاكرة التخزين المؤقت LiteSpeed',
        ],

        'acl' => [
            'title' => 'ذاكرة التخزين المؤقت LiteSpeed',
            'view'  => 'عرض',
            'purge' => 'مسح',
        ],

        'page' => [
            'title' => 'ذاكرة التخزين المؤقت LiteSpeed',
            'info'  => 'مراجعة وسوم الكاش وتنفيذ عمليات مسح محددة لأغراض الصيانة واستكشاف الأخطاء في بيئة الإنتاج.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'إجراءات سريعة',
                'info'  => 'استخدم إجراءات المسح السريعة للصفحة الرئيسية أو، عند الضرورة القصوى، لمسح الكاش بالكامل.',
            ],

            'debug' => [
                'title'         => 'رؤوس التصحيح',
                'tag-label'     => 'الوسم:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'السياسة:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'المسح:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'المسح حسب الفئة',
                'info'  => 'ابحث عن فئة وقم بمسح الوسم المرتبط بمعرفها فقط.',
            ],

            'product' => [
                'title' => 'المسح حسب المنتج',
                'info'  => 'ابحث عن منتج وقم بمسح الوسم المرتبط بمعرفه فقط.',
            ],

            'tag' => [
                'title' => 'المسح حسب الوسم',
                'info'  => 'أدخل وسماً أو أكثر من وسوم LiteSpeed مفصولة بفواصل أو مسافات.',
            ],

            'url' => [
                'title' => 'المسح حسب الرابط',
                'info'  => 'إرسال طلب مسح لمسار URL محدد (نسبي أو كامل).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'مسح كامل الكاش',
            'purge-home'     => 'مسح الصفحة الرئيسية',
            'purge-category' => 'مسح الفئة',
            'purge-product'  => 'مسح المنتج',
            'purge-tag'      => 'مسح الوسم',
            'purge-url'      => 'مسح الرابط',
        ],

        'badges' => [
            'id-tag'     => 'وسم المعرف',
            'manual'     => 'يدوي',
            'exact-path' => 'مسار دقيق',
        ],

        'fields' => [
            'category' => 'الفئة',
            'product'  => 'المنتج',
            'tags'     => 'الوسوم',
            'url'      => 'الرابط',
        ],

        'labels' => [
            'target-tag' => 'الوسم المستهدف:',
        ],

        'placeholders' => [
            'category' => 'ابحث عن الفئات بالاسم أو الرابط (slug) أو المعرف',
            'product'  => 'ابحث عن المنتجات بالاسم',
            'tags'     => 'مثال: category_5 product_22 home',
            'url'      => 'مثال: /footwears أو https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'اختر فئة مطابقة بشكل دقيق قبل الإرسال.',
            'product'  => 'اختر منتجاً مطابقاً بشكل دقيق قبل الإرسال.',
            'tags'     => 'استخدام العلامة (*) غير مسموح هنا. استخدم مسح الكل بدلاً من ذلك.',
            'url'      => 'يتم تحويل الطلب إلى مسار المتجر قبل إرساله إلى LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'هل تريد مسح كامل ذاكرة التخزين المؤقت؟ هذا إجراء غير قابل للتراجع.',
            'home'     => 'هل تريد مسح كاش الصفحة الرئيسية؟',
            'category' => 'هل تريد مسح كاش الفئة المحددة؟',
            'product'  => 'هل تريد مسح كاش المنتج المحدد؟',
            'tag'      => 'هل تريد مسح الوسوم المدخلة؟',
            'url'      => 'هل تريد مسح الرابط المحدد؟',
        ],

        'flash' => [
            'purge-all' => 'تم إرسال طلب مسح كامل كاش LiteSpeed.',
            'home'      => 'تم إرسال طلب مسح الصفحة الرئيسية.',
            'category'  => 'تم إرسال طلب مسح للفئة :name.',
            'product'   => 'تم إرسال طلب مسح للمنتج :name.',
            'tags'      => 'تم إرسال طلب مسح الوسوم.',
            'url'       => 'تم إرسال طلب مسح الرابط :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'قم بتكوين إعدادات LiteSpeed Cache لتحسين الأداء.',
                'title' => 'ذاكرة التخزين المؤقت LiteSpeed',

                'configuration' => [
                    'info'  => 'إدارة إعدادات LiteSpeed Cache والتكوينات المرتبطة بها.',
                    'title' => 'الإعدادات',

                    'cache-application' => [
                        'info'             => 'تحديد خيارات تطبيق الكاش.',
                        'title'            => 'تطبيق الكاش',
                        'title-info'       => 'تكوين LiteSpeed Cache: تفعيل/تعطيل الكاش وتحديد مدة TTL الافتراضية.',
                        'status'           => 'الحالة',
                        'default-ttl'      => 'مدة TTL الافتراضية (وقت البقاء)',
                        'default-ttl-info' => 'تحديد مدة بقاء العناصر في الكاش بالثواني <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">مزيد من المعلومات</a>.',
                        'debug-mode'       => 'وضع التصحيح',
                        'debug-mode-info'  => 'عند التفعيل، يتم إضافة رؤوس تصحيح تعرض وسوم LiteSpeed وقرارات التحكم في الكاش وعمليات المسح الخاصة بالطلب.',
                        'cache-path'       => 'مسار الكاش',
                        'cache-path-info'  => 'المسار الكامل لدليل الكاش الخاص بـ LiteSpeed. الافتراضي: /usr/local/lsws/cachedata/priv (Enterprise) أو /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
