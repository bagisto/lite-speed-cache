<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'উন্নত কর্মক্ষমতার জন্য LSCache সেটিংস কনফিগার করুন।',
                'title' => 'LiteSpeed ক্যাশ',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache অ্যাপ্লিকেশন এবং সম্পর্কিত সেটিংস পরিচালনা করুন।',
                    'title' => 'কনফিগারেশন',

                    'cache-application' => [
                        'info'             => 'ক্যাশ অ্যাপ্লিকেশন বিকল্প নির্ধারণ করুন।',
                        'title'            => 'ক্যাশ অ্যাপ্লিকেশন',
                        'title-info'       => 'LiteSpeed ক্যাশ কনফিগার করুন: ক্যাশিং সক্ষম/অক্ষম করুন, ডিফল্ট TTL সেট করুন, এবং শুধুমাত্র অতিথিদের জন্য ক্যাশিং নির্বাচন করুন।',
                        'status'           => 'অবস্থা',
                        'default-ttl'      => 'ডিফল্ট TTL (Time To Live)',
                        'default-ttl-info' => 'ক্যাশ করা আইটেমগুলোর জন্য ডিফল্ট জীবনকাল (সেকেন্ডে) নির্ধারণ করুন <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">আরও তথ্য</a>.',
                        'guest-only'       => 'শুধু অতিথিদের জন্য',
                        'guest-only-info'  => 'শুধু অতিথি ব্যবহারকারীদের জন্য ক্যাশ সক্ষম করুন। যদি অক্ষম করা হয়, ক্যাশ সব ব্যবহারকারীর জন্য প্রযোজ্য হবে।',
                    ],
                ],
            ],
        ],
    ],
];
