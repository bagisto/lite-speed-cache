<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'מטמון LiteSpeed',
        ],

        'acl' => [
            'title' => 'מטמון LiteSpeed',
            'view'  => 'צפייה',
            'purge' => 'ניקוי',
        ],

        'page' => [
            'title' => 'מטמון LiteSpeed',
            'info'  => 'סקור תגיות מטמון והפעל ניקוי ממוקד לצורך פתרון תקלות ותחזוקה בסביבת ייצור.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'פעולות מהירות',
                'info'  => 'השתמש בפעולות ניקוי מהירות לדף הבית או, כאשר יש צורך, לכל המטמון.',
            ],

            'debug' => [
                'title'         => 'כותרות דיבאג',
                'tag-label'     => 'תגית:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'מדיניות:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'ניקוי:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'ניקוי לפי קטגוריה',
                'info'  => 'חפש קטגוריה ונקה רק את התגית המבוססת על ה-ID שלה.',
            ],

            'product' => [
                'title' => 'ניקוי לפי מוצר',
                'info'  => 'חפש מוצר ונקה רק את התגית המבוססת על ה-ID שלו.',
            ],

            'tag' => [
                'title' => 'ניקוי לפי תגית',
                'info'  => 'הזן תגית LiteSpeed אחת או יותר מופרדות בפסיקים או רווחים.',
            ],

            'url' => [
                'title' => 'ניקוי לפי URL',
                'info'  => 'שלח בקשת ניקוי עבור נתיב URL מדויק (יחסי או מלא).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'נקה את כל המטמון',
            'purge-home'     => 'נקה דף הבית',
            'purge-category' => 'נקה קטגוריה',
            'purge-product'  => 'נקה מוצר',
            'purge-tag'      => 'נקה תגית',
            'purge-url'      => 'נקה URL',
        ],

        'badges' => [
            'id-tag'     => 'תגית ID',
            'manual'     => 'ידני',
            'exact-path' => 'נתיב מדויק',
        ],

        'fields' => [
            'category' => 'קטגוריה',
            'product'  => 'מוצר',
            'tags'     => 'תגיות',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'תגית יעד:',
        ],

        'placeholders' => [
            'category' => 'חפש קטגוריות לפי שם, slug או ID',
            'product'  => 'חפש מוצרים לפי שם',
            'tags'     => 'דוגמה: category_5 product_22 home',
            'url'      => 'דוגמה: /footwears או https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'בחר התאמה מדויקת של קטגוריה לפני שליחה.',
            'product'  => 'בחר התאמה מדויקת של מוצר לפני שליחה.',
            'tags'     => 'התו הכללי (*) חסום כאן. השתמש ב"נקה את כל המטמון" במקום.',
            'url'      => 'הבקשה מומרת לנתיב storefront לפני שליחתה ל-LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'האם לנקות את כל מטמון LiteSpeed? פעולה זו הרסנית.',
            'home'     => 'האם לנקות את תגיות המטמון של דף הבית?',
            'category' => 'האם לנקות את תגית המטמון של הקטגוריה שנבחרה?',
            'product'  => 'האם לנקות את תגית המטמון של המוצר שנבחר?',
            'tag'      => 'האם לנקות את רשימת תגיות LiteSpeed שסופקה?',
            'url'      => 'האם לנקות את נתיב ה-URL שסופק?',
        ],

        'flash' => [
            'purge-all' => 'בקשת ניקוי מלאה של מטמון LiteSpeed נשלחה.',
            'home'      => 'בקשת ניקוי דף הבית נשלחה.',
            'category'  => 'בקשת ניקוי נשלחה עבור קטגוריה :name.',
            'product'   => 'בקשת ניקוי נשלחה עבור מוצר :name.',
            'tags'      => 'בקשת ניקוי תגיות נשלחה.',
            'url'       => 'בקשת ניקוי URL נשלחה עבור :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'הגדר את אפשרויות LSCache לשיפור הביצועים.',
                'title' => 'מטמון LiteSpeed',

                'configuration' => [
                    'info'  => 'נהל את אפליקציית LiteSpeed Cache ואת ההגדרות הקשורות.',
                    'title' => 'תצורה',

                    'cache-application' => [
                        'info'             => 'הגדר אפשרויות אפליקציית המטמון.',
                        'title'            => 'אפליקציית מטמון',
                        'title-info'       => 'הגדר LiteSpeed Cache: הפעלה/כיבוי של המטמון והגדרת TTL ברירת מחדל.',
                        'status'           => 'סטטוס',
                        'default-ttl'      => 'TTL ברירת מחדל (זמן חיים)',
                        'default-ttl-info' => 'הגדר את זמן החיים ברירת המחדל של פריטי מטמון בשניות <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">מידע נוסף</a>.',
                        'debug-mode'       => 'מצב דיבאג',
                        'debug-mode-info'  => 'כאשר מופעל, מוסיף כותרות דיבאג המציגות תגיות LiteSpeed, החלטות cache-control ופעולות ניקוי לפי בקשה.',
                        'cache-path'       => 'נתיב המטמון',
                        'cache-path-info'  => 'נתיב מלא לתיקיית המטמון הפרטית של LiteSpeed. ברירת מחדל: /usr/local/lsws/cachedata/priv (Enterprise) או /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
