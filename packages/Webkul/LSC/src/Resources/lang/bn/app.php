<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed ক্যাশ',
        ],

        'acl' => [
            'title' => 'LiteSpeed ক্যাশ',
            'view'  => 'দেখুন',
            'purge' => 'মুছুন',
        ],

        'page' => [
            'title' => 'LiteSpeed ক্যাশ',
            'info'  => 'প্রোডাকশন ট্রাবলশুটিং এবং মেইনটেন্যান্সের জন্য ক্যাশ ট্যাগ পর্যালোচনা করুন এবং নির্দিষ্টভাবে মুছে ফেলুন।',
        ],

        'cards' => [
            'quick' => [
                'title' => 'দ্রুত কার্যক্রম',
                'info'  => 'হোমপেজের জন্য দ্রুত purge ব্যবহার করুন অথবা প্রয়োজনে সম্পূর্ণ ক্যাশ মুছে ফেলুন।',
            ],

            'debug' => [
                'title'         => 'ডিবাগ হেডার',
                'tag-label'     => 'ট্যাগ:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'নীতি:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'মুছুন:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'ক্যাটাগরি অনুযায়ী মুছুন',
                'info'  => 'একটি ক্যাটাগরি খুঁজুন এবং শুধুমাত্র তার ID-ভিত্তিক ট্যাগ মুছুন।',
            ],

            'product' => [
                'title' => 'প্রোডাক্ট অনুযায়ী মুছুন',
                'info'  => 'একটি প্রোডাক্ট খুঁজুন এবং শুধুমাত্র তার ID-ভিত্তিক ট্যাগ মুছুন।',
            ],

            'tag' => [
                'title' => 'ট্যাগ অনুযায়ী মুছুন',
                'info'  => 'কমা বা স্পেস দিয়ে আলাদা করে এক বা একাধিক LiteSpeed ট্যাগ লিখুন।',
            ],

            'url' => [
                'title' => 'URL অনুযায়ী মুছুন',
                'info'  => 'নির্দিষ্ট URL পথের জন্য purge অনুরোধ পাঠান (relative বা full URL)।',
            ],
        ],

        'actions' => [
            'purge-all'      => 'সমস্ত ক্যাশ মুছুন',
            'purge-home'     => 'হোমপেজ মুছুন',
            'purge-category' => 'ক্যাটাগরি মুছুন',
            'purge-product'  => 'প্রোডাক্ট মুছুন',
            'purge-tag'      => 'ট্যাগ মুছুন',
            'purge-url'      => 'URL মুছুন',
        ],

        'badges' => [
            'id-tag'     => 'ID ট্যাগ',
            'manual'     => 'ম্যানুয়াল',
            'exact-path' => 'সঠিক পথ',
        ],

        'fields' => [
            'category' => 'ক্যাটাগরি',
            'product'  => 'প্রোডাক্ট',
            'tags'     => 'ট্যাগসমূহ',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'টার্গেট ট্যাগ:',
        ],

        'placeholders' => [
            'category' => 'নাম, slug বা ID দিয়ে ক্যাটাগরি খুঁজুন',
            'product'  => 'নাম দিয়ে প্রোডাক্ট খুঁজুন',
            'tags'     => 'উদাহরণ: category_5 product_22 home',
            'url'      => 'উদাহরণ: /footwears অথবা https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'সাবমিট করার আগে সঠিক ক্যাটাগরি নির্বাচন করুন।',
            'product'  => 'সাবমিট করার আগে সঠিক প্রোডাক্ট নির্বাচন করুন।',
            'tags'     => 'Wildcard (*) এখানে অনুমোদিত নয়। এর পরিবর্তে Purge All ব্যবহার করুন।',
            'url'      => 'LiteSpeed-এ পাঠানোর আগে অনুরোধটি storefront path-এ রূপান্তর করা হয়।',
        ],

        'confirm' => [
            'all'      => 'আপনি কি পুরো LiteSpeed ক্যাশ মুছে ফেলতে চান? এটি একটি ঝুঁকিপূর্ণ কাজ।',
            'home'     => 'আপনি কি হোমপেজ ক্যাশ মুছে ফেলতে চান?',
            'category' => 'আপনি কি নির্বাচিত ক্যাটাগরির ক্যাশ মুছে ফেলতে চান?',
            'product'  => 'আপনি কি নির্বাচিত প্রোডাক্টের ক্যাশ মুছে ফেলতে চান?',
            'tag'      => 'আপনি কি প্রদত্ত ট্যাগসমূহ মুছে ফেলতে চান?',
            'url'      => 'আপনি কি প্রদত্ত URL path মুছে ফেলতে চান?',
        ],

        'flash' => [
            'purge-all' => 'LiteSpeed ক্যাশ সম্পূর্ণ মুছে ফেলার অনুরোধ পাঠানো হয়েছে।',
            'home'      => 'হোমপেজ purge অনুরোধ পাঠানো হয়েছে।',
            'category'  => 'ক্যাটাগরি :name এর জন্য purge অনুরোধ পাঠানো হয়েছে।',
            'product'   => 'প্রোডাক্ট :name এর জন্য purge অনুরোধ পাঠানো হয়েছে।',
            'tags'      => 'ট্যাগ purge অনুরোধ পাঠানো হয়েছে।',
            'url'       => ':url এর জন্য URL purge অনুরোধ পাঠানো হয়েছে।',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'পারফরম্যান্স উন্নত করার জন্য LiteSpeed Cache সেটিংস কনফিগার করুন।',
                'title' => 'LiteSpeed ক্যাশ',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache এবং সংশ্লিষ্ট সেটিংস পরিচালনা করুন।',
                    'title' => 'কনফিগারেশন',

                    'cache-application' => [
                        'info'             => 'ক্যাশ অ্যাপ্লিকেশন অপশন নির্ধারণ করুন।',
                        'title'            => 'ক্যাশ অ্যাপ্লিকেশন',
                        'title-info'       => 'LiteSpeed Cache কনফিগার করুন: ক্যাশ চালু/বন্ধ এবং ডিফল্ট TTL নির্ধারণ করুন।',
                        'status'           => 'স্ট্যাটাস',
                        'default-ttl'      => 'ডিফল্ট TTL (Time To Live)',
                        'default-ttl-info' => 'ক্যাশ আইটেম কতক্ষণ থাকবে তা সেকেন্ডে নির্ধারণ করুন <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">আরও জানুন</a>।',
                        'debug-mode'       => 'ডিবাগ মোড',
                        'debug-mode-info'  => 'চালু থাকলে LiteSpeed ট্যাগ, cache-control এবং purge অপারেশন দেখানোর জন্য ডিবাগ হেডার যোগ করা হবে।',
                        'cache-path'       => 'ক্যাশ পাথ',
                        'cache-path-info'  => 'LiteSpeed private cache ডিরেক্টরির সম্পূর্ণ পাথ। ডিফল্ট: /usr/local/lsws/cachedata/priv (Enterprise) অথবা /tmp/lshttpd/lscache (OpenLiteSpeed)।',
                    ],
                ],
            ],
        ],
    ],
];
