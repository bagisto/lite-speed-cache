<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'برای بهبود عملکرد، تنظیمات LSCache را پیکربندی کنید.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'برنامه LiteSpeed Cache و تنظیمات مرتبط را مدیریت کنید.',
                    'title' => 'پیکربندی',

                    'cache-application' => [
                        'default-ttl-info' => 'زمان ماندگاری پیش‌فرض برای آیتم‌های کش‌شده را بر حسب ثانیه مشخص کنید <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">اطلاعات بیشتر</a>.',
                        'default-ttl'      => 'TTL پیش‌فرض (زمان ماندگاری)',
                        'guest-only-info'  => 'فعال کردن کش فقط برای کاربران مهمان. در صورت غیرفعال بودن، کش برای همه کاربران اعمال خواهد شد.',
                        'guest-only'       => 'فقط برای مهمان‌ها',
                        'info'             => 'گزینه‌های برنامهٔ کش را تنظیم کنید.',
                        'status'           => 'وضعیت',
                        'title-info'       => 'پیکربندی LiteSpeed Cache: فعال/غیرفعال کردن کش، تنظیم TTL پیش‌فرض و انتخاب کش فقط برای مهمان‌ها.',
                        'title'            => 'برنامهٔ کش',
                    ],
                ],
            ],
        ],
    ],
];
