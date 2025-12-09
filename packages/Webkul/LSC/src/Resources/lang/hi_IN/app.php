<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'बेहतर प्रदर्शन के लिए LSCache सेटिंग्स कॉन्फ़िगर करें।',
                'title' => 'LiteSpeed कैश',

                'configuration' => [
                    'info'  => 'LiteSpeed Cache एप्लिकेशन और संबंधित सेटिंग्स प्रबंधित करें।',
                    'title' => 'कॉन्फ़िगरेशन',

                    'cache-application' => [
                        'default-ttl-info' => 'कॅश किए गए आइटमों के लिए डिफ़ॉल्ट जीवनकाल (सेकंड में) सेट करें <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">अधिक जानकारी</a>.',
                        'default-ttl'      => 'डिफ़ॉल्ट TTL (टाइम टू लाइव)',
                        'guest-only-info'  => 'केवल अतिथि उपयोगकर्ताओं के लिए कैशिंग सक्षम करें। यदि अक्षम किया गया है, तो कैशिंग सभी उपयोगकर्ताओं पर लागू होगी।',
                        'guest-only'       => 'केवल अतिथि',
                        'info'             => 'कैश एप्लिकेशन विकल्प सेट करें।',
                        'status'           => 'स्थिति',
                        'title-info'       => 'LiteSpeed कैश को कॉन्फ़िगर करें: कैशिंग सक्षम/अक्षम करें, डिफ़ॉल्ट TTL सेट करें, और केवल अतिथि के लिए कैशिंग चुनें।',
                        'title'            => 'कैश एप्लिकेशन',
                    ],
                ],
            ],
        ],
    ],
];
