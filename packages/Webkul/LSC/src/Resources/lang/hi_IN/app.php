<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed कैश',
        ],

        'acl' => [
            'title' => 'LiteSpeed कैश',
            'view'  => 'देखें',
            'purge' => 'साफ करें',
        ],

        'page' => [
            'title' => 'LiteSpeed कैश',
            'info'  => 'कैश टैग की समीक्षा करें और प्रोडक्शन ट्रबलशूटिंग व मेंटेनेंस के लिए लक्षित purge चलाएँ।',
        ],

        'cards' => [
            'quick' => [
                'title' => 'त्वरित कार्य',
                'info'  => 'होमपेज के लिए तेज purge क्रियाएँ उपयोग करें या आवश्यकता होने पर पूरे कैश को साफ करें।',
            ],

            'debug' => [
                'title'         => 'डिबग हेडर',
                'tag-label'     => 'टैग:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'नीति:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'पर्ज:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'कैटेगरी के अनुसार साफ करें',
                'info'  => 'एक कैटेगरी खोजें और केवल उसके ID-आधारित टैग को साफ करें।',
            ],

            'product' => [
                'title' => 'प्रोडक्ट के अनुसार साफ करें',
                'info'  => 'एक प्रोडक्ट खोजें और केवल उसके ID-आधारित टैग को साफ करें।',
            ],

            'tag' => [
                'title' => 'टैग के अनुसार साफ करें',
                'info'  => 'एक या अधिक LiteSpeed टैग दर्ज करें, जिन्हें कॉमा या स्पेस से अलग किया गया हो।',
            ],

            'url' => [
                'title' => 'URL के अनुसार साफ करें',
                'info'  => 'किसी सटीक URL पथ के लिए purge अनुरोध भेजें (relative या पूर्ण URL)।',
            ],
        ],

        'actions' => [
            'purge-all'      => 'संपूर्ण कैश साफ करें',
            'purge-home'     => 'होम पेज साफ करें',
            'purge-category' => 'कैटेगरी साफ करें',
            'purge-product'  => 'प्रोडक्ट साफ करें',
            'purge-tag'      => 'टैग साफ करें',
            'purge-url'      => 'URL साफ करें',
        ],

        'badges' => [
            'id-tag'     => 'ID टैग',
            'manual'     => 'मैनुअल',
            'exact-path' => 'सटीक पथ',
        ],

        'fields' => [
            'category' => 'कैटेगरी',
            'product'  => 'प्रोडक्ट',
            'tags'     => 'टैग',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'लक्ष्य टैग:',
        ],

        'placeholders' => [
            'category' => 'नाम, slug या ID से कैटेगरी खोजें',
            'product'  => 'नाम से प्रोडक्ट खोजें',
            'tags'     => 'उदाहरण: category_5 product_22 home',
            'url'      => 'उदाहरण: /footwears या https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'सबमिट करने से पहले एक सही कैटेगरी चुनें।',
            'product'  => 'सबमिट करने से पहले एक सही प्रोडक्ट चुनें।',
            'tags'     => 'Wildcard (*) यहाँ ब्लॉक किया गया है। इसके बजाय “संपूर्ण कैश साफ करें” उपयोग करें।',
            'url'      => 'अनुरोध LiteSpeed को भेजने से पहले storefront पथ में परिवर्तित किया जाता है।',
        ],

        'confirm' => [
            'all'      => 'क्या आप पूरा LiteSpeed कैश साफ करना चाहते हैं? यह एक विनाशकारी क्रिया है।',
            'home'     => 'क्या आप होम पेज के कैश टैग साफ करना चाहते हैं?',
            'category' => 'क्या आप चयनित कैटेगरी का कैश टैग साफ करना चाहते हैं?',
            'product'  => 'क्या आप चयनित प्रोडक्ट का कैश टैग साफ करना चाहते हैं?',
            'tag'      => 'क्या आप दिए गए LiteSpeed टैग की सूची साफ करना चाहते हैं?',
            'url'      => 'क्या आप दिए गए URL पथ को साफ करना चाहते हैं?',
        ],

        'flash' => [
            'purge-all' => 'LiteSpeed कैश को पूरी तरह साफ करने का अनुरोध भेजा गया।',
            'home'      => 'होम पेज purge अनुरोध भेजा गया।',
            'category'  => 'कैटेगरी :name के लिए purge अनुरोध भेजा गया।',
            'product'   => 'प्रोडक्ट :name के लिए purge अनुरोध भेजा गया।',
            'tags'      => 'टैग purge अनुरोध भेजा गया।',
            'url'       => 'URL :url के लिए purge अनुरोध भेजा गया।',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'बेहतर प्रदर्शन के लिए LSCache सेटिंग्स कॉन्फ़िगर करें।',
                'title' => 'LiteSpeed कैश',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache एप्लिकेशन और संबंधित सेटिंग्स प्रबंधित करें।',
                    'title' => 'कॉन्फ़िगरेशन',

                    'cache-application' => [
                        'info'             => 'कैश एप्लिकेशन विकल्प सेट करें।',
                        'title'            => 'कैश एप्लिकेशन',
                        'title-info'       => 'LiteSpeed Cache कॉन्फ़िगर करें: कैश सक्षम/अक्षम करें और डिफ़ॉल्ट TTL सेट करें।',
                        'status'           => 'स्थिति',
                        'default-ttl'      => 'डिफ़ॉल्ट TTL (Time To Live)',
                        'default-ttl-info' => 'कैश आइटम्स के लिए डिफ़ॉल्ट समय (सेकंड में) सेट करें <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">अधिक जानकारी</a>।',
                        'debug-mode'       => 'डिबग मोड',
                        'debug-mode-info'  => 'सक्रिय होने पर, LiteSpeed टैग, cache-control निर्णय और purge ऑपरेशन दिखाने वाले डिबग हेडर जोड़ता है।',
                        'cache-path'       => 'कैश पथ',
                        'cache-path-info'  => 'LiteSpeed प्राइवेट कैश डायरेक्टरी का पूर्ण पथ। डिफ़ॉल्ट: /usr/local/lsws/cachedata/priv (Enterprise) या /tmp/lshttpd/lscache (OpenLiteSpeed)।',
                    ],
                ],
            ],
        ],
    ],
];
