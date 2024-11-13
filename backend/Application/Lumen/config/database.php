<?php

return [
    'default' => env('DB_CONNECTION', 'doctrine'),

    'connections' => [
        'doctrine' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public'
        ],
        'pgsql' => [
            'driver'        => 'pgsql',
            'host'          => env('DB_HOST', 'localhost'),
            'port'          => env('DB_PORT', '5432'),
            'database'      => env('DB_DATABASE', ''),
            'username'      => env('DB_USERNAME', ''),
            'password'      => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => env('DB_SCHEMA', 'public'),
        ],
    ],

    'migrations' => 'migrations',

    'redis' => [
        'client' => 'predis',
        'options' => [
            'prefix' => '',
        ],
        'default' => [
            'host' => env('REDIS_HOST', 'redis'),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],
        'queue' => [
            'host' => env('REDIS_HOST', 'redis'),
            'port' => env('REDIS_PORT', 6379),
            'database' => 4,
        ],

        'socket' => [
            'host' => env('REDIS_HOST', 'redis'),
            'port' => env('REDIS_PORT', 6379),
            'database' =>  5,
        ],
        'cache' => [
            'host' => env('REDIS_HOST', 'redis'),
            'port' => env('REDIS_PORT', 6379),
            'database' =>  2,
        ],

    ],



];
