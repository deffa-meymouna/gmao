<?php
return [
    'translator' => [
        'locale' => 'fr_FR',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../../vendor/zf-commons/zfc-user/src/ZfcUser/language',
                'pattern' => '%s.mo'// <-%s is important    
            ] 

        ]
    ]
];