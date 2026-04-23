<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'کش LiteSpeed',
        ],

        'acl' => [
            'title' => 'کش LiteSpeed',
            'view'  => 'مشاهده',
            'purge' => 'پاک‌سازی',
        ],

        'page' => [
            'title' => 'کش LiteSpeed',
            'info'  => 'برچسب‌های کش را بررسی کنید و برای عیب‌یابی و نگهداری در محیط عملیاتی، پاک‌سازی‌های هدفمند انجام دهید.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'اقدامات سریع',
                'info'  => 'از پاک‌سازی سریع برای صفحه اصلی استفاده کنید یا در صورت لزوم، کل کش را پاک کنید.',
            ],

            'debug' => [
                'title'         => 'هدرهای دیباگ',
                'tag-label'     => 'برچسب:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'سیاست:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'پاک‌سازی:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'پاک‌سازی بر اساس دسته‌بندی',
                'info'  => 'یک دسته‌بندی را جستجو کرده و فقط برچسب مبتنی بر شناسه آن را پاک کنید.',
            ],

            'product' => [
                'title' => 'پاک‌سازی بر اساس محصول',
                'info'  => 'یک محصول را جستجو کرده و فقط برچسب مبتنی بر شناسه آن را پاک کنید.',
            ],

            'tag' => [
                'title' => 'پاک‌سازی بر اساس برچسب',
                'info'  => 'یک یا چند برچسب LiteSpeed را با کاما یا فاصله وارد کنید.',
            ],

            'url' => [
                'title' => 'پاک‌سازی بر اساس URL',
                'info'  => 'درخواست پاک‌سازی برای مسیر دقیق URL (نسبی یا کامل) ارسال کنید.',
            ],
        ],

        'actions' => [
            'purge-all'      => 'پاک‌سازی کامل کش',
            'purge-home'     => 'پاک‌سازی صفحه اصلی',
            'purge-category' => 'پاک‌سازی دسته‌بندی',
            'purge-product'  => 'پاک‌سازی محصول',
            'purge-tag'      => 'پاک‌سازی برچسب',
            'purge-url'      => 'پاک‌سازی URL',
        ],

        'badges' => [
            'id-tag'     => 'برچسب شناسه',
            'manual'     => 'دستی',
            'exact-path' => 'مسیر دقیق',
        ],

        'fields' => [
            'category' => 'دسته‌بندی',
            'product'  => 'محصول',
            'tags'     => 'برچسب‌ها',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'برچسب هدف:',
        ],

        'placeholders' => [
            'category' => 'جستجوی دسته‌بندی با نام، slug یا شناسه',
            'product'  => 'جستجوی محصول با نام',
            'tags'     => 'مثال: category_5 product_22 home',
            'url'      => 'مثال: /footwears یا https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'قبل از ارسال، یک دسته‌بندی دقیق انتخاب کنید.',
            'product'  => 'قبل از ارسال، یک محصول دقیق انتخاب کنید.',
            'tags'     => 'کاراکتر * (وایلدکارد) در اینجا مجاز نیست. به‌جای آن از «پاک‌سازی کامل کش» استفاده کنید.',
            'url'      => 'درخواست قبل از ارسال به LiteSpeed به مسیر فروشگاه تبدیل می‌شود.',
        ],

        'confirm' => [
            'all'      => 'آیا می‌خواهید کل کش LiteSpeed را پاک کنید؟ این یک عملیات مخرب است.',
            'home'     => 'آیا می‌خواهید کش صفحه اصلی پاک شود؟',
            'category' => 'آیا می‌خواهید کش دسته‌بندی انتخاب‌شده پاک شود؟',
            'product'  => 'آیا می‌خواهید کش محصول انتخاب‌شده پاک شود؟',
            'tag'      => 'آیا می‌خواهید برچسب‌های واردشده پاک شوند؟',
            'url'      => 'آیا می‌خواهید مسیر URL واردشده پاک شود؟',
        ],

        'flash' => [
            'purge-all' => 'درخواست پاک‌سازی کامل کش LiteSpeed ارسال شد.',
            'home'      => 'درخواست پاک‌سازی صفحه اصلی ارسال شد.',
            'category'  => 'درخواست پاک‌سازی برای دسته‌بندی :name ارسال شد.',
            'product'   => 'درخواست پاک‌سازی برای محصول :name ارسال شد.',
            'tags'      => 'درخواست پاک‌سازی برچسب‌ها ارسال شد.',
            'url'       => 'درخواست پاک‌سازی URL برای :url ارسال شد.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'تنظیمات LSCache را برای بهبود عملکرد پیکربندی کنید.',
                'title' => 'کش LiteSpeed',

                'configuration' => [
                    'info'  => 'برنامه LiteSpeed Cache و تنظیمات مرتبط را مدیریت کنید.',
                    'title' => 'پیکربندی',

                    'cache-application' => [
                        'info'             => 'گزینه‌های برنامه کش را تنظیم کنید.',
                        'title'            => 'برنامه کش',
                        'title-info'       => 'LiteSpeed Cache را پیکربندی کنید: فعال/غیرفعال‌سازی کش و تعیین TTL پیش‌فرض.',
                        'status'           => 'وضعیت',
                        'default-ttl'      => 'TTL پیش‌فرض (زمان ماندگاری)',
                        'default-ttl-info' => 'زمان ماندگاری پیش‌فرض برای آیتم‌های کش را به ثانیه تعیین کنید <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">اطلاعات بیشتر</a>.',
                        'debug-mode'       => 'حالت دیباگ',
                        'debug-mode-info'  => 'در صورت فعال بودن، هدرهای دیباگ اضافه می‌شود که برچسب‌های LiteSpeed، تصمیمات cache-control و عملیات پاک‌سازی را نمایش می‌دهد.',
                        'cache-path'       => 'مسیر کش',
                        'cache-path-info'  => 'مسیر کامل دایرکتوری کش خصوصی LiteSpeed. پیش‌فرض: /usr/local/lsws/cachedata/priv (Enterprise) یا /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
