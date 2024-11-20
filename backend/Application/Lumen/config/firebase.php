<?php

declare(strict_types=1);

return [
    'default' => env('FIREBASE_PROJECT', 'alga-crm-system'),

    'projects' => [
        'alga-crm-system' => [
            'credentials' => env('FIREBASE_USE') && file_exists(env('FIREBASE_CREDENTIALS')) ? file_get_contents(env('FIREBASE_CREDENTIALS')) : false,
        ]
    ]
];

