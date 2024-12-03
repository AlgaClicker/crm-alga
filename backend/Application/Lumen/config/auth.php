<?php

return [
    'defaults' => [
        'guard' => 'jwt',
        'passwords' => 'accounts',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'accounts',
        ],
        'web' => [
            'driver' => 'session',
            'provider' => 'jwt',
        ],
        'jwt' => [
            'driver' => 'jwt',
            'provider' => 'accounts',
            'hash' => false,
        ],
        'firebase' => [
            'driver' => 'firebase',
            'provider' => 'accounts',
            'hash' => false,
        ]
    ],
    'providers' => [
        'accounts' => [
            'driver' => 'doctrine',
            'model' => Domain\Entities\Subscriber\Account::class,
        ],
        'jwt' => [
            'driver' => 'doctrine',
            'model' => Domain\Entities\Subscriber\Account::class,
        ]

    ]
];
