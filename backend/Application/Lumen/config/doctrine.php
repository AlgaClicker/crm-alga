<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entity Mangers
    |--------------------------------------------------------------------------
    |
    | Configure your Entity Managers here. You can set a different connection
    | and driver per manager and configure events and filters. Change the
    | paths setting to the appropriate path and replace App namespace
    | by your own namespace.
    |
    | Available meta drivers: annotations|yaml|xml|config|static_php
    |
    | Available connections: mysql|oracle|pgsql|sqlite|sqlsrv
    | (Connections can be configured in the database config)
    |
    | --> Warning: Proxy auto generation should only be enabled in dev!
    |
    */
    'managers'                  => [
        'default' => [
            'dev'        => env('APP_DEBUG'),
            'meta'       => 'yaml',
            'connection' => 'doctrine',

            'namespaces' => [
                'Domain\Entities'
            ],
            'paths'      => [
                base_path('../../Infrastructure/Doctrine/Mappings'),
            ],
            'repository' => Doctrine\ORM\EntityRepository::class,
            'proxies'    => [
                'namespace'     => false,
                'path'          => base_path('../../Infrastructure/Doctrine/Proxies'),
                'auto_generate' => true
            ],
            /*
            |--------------------------------------------------------------------------
            | Doctrine events
            |--------------------------------------------------------------------------
            |
            | The listener array expects the key to be a Doctrine event
            | e.g. Doctrine\ORM\Events::onFlush
            |
            */
            'events'     => [
                'listeners'   => [],
                'subscribers' => []
            ],
            'filters'    => []
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine Extensions
    |--------------------------------------------------------------------------
    |
    | Enable/disable Doctrine Extensions by adding or removing them from the list
    |
    | If you want to require custom extensions you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'extensions'                => [
        //LaravelDoctrine\ORM\Extensions\TablePrefix\TablePrefixExtension::class,
        LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
        LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
        //LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
        LaravelDoctrine\Extensions\Sortable\SortableExtension::class,
        LaravelDoctrine\Extensions\Tree\TreeExtension::class,
        //LaravelDoctrine\Extensions\Loggable\LoggableExtension::class,
        //LaravelDoctrine\Extensions\Blameable\BlameableExtension::class,
        //LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension::class,
        //LaravelDoctrine\Extensions\Translatable\TranslatableExtension::class,
        LaravelDoctrine\Scout\SearchableExtension::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom types
    |--------------------------------------------------------------------------
    */
    'custom_types'              => [
        'json' => LaravelDoctrine\ORM\Types\Json::class,
        \Ramsey\Uuid\Doctrine\UuidType::NAME => \Ramsey\Uuid\Doctrine\UuidType::class
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom datetime functions
    |--------------------------------------------------------------------------
    */
    'custom_datetime_functions' => [],
    /*
    |--------------------------------------------------------------------------
    | DQL custom numeric functions
    |--------------------------------------------------------------------------
    */
    'custom_numeric_functions'  => [],
    /*
    |--------------------------------------------------------------------------
    | DQL custom string functions
    |--------------------------------------------------------------------------
    */
    'custom_string_functions'   => [],
    /*
    |--------------------------------------------------------------------------
    | Enable query logging with laravel file logging,
    | debugbar, clockwork or an own implementation.
    | Setting it to false, will disable logging
    |
    | Available:
    | - LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger
    | - LaravelDoctrine\ORM\Loggers\ClockworkLogger
    | - LaravelDoctrine\ORM\Loggers\FileLogger
    |--------------------------------------------------------------------------
    */
    'logger'                    => env("LaravelDoctrine\ORM\Loggers\FileLogger::class", false),
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure meta-data, query and result caching here.
    | Optionally you can enable second level caching.
    |
    | Available: acp|array|file|memcached|redis
    |
    */
    'cache'                     => [
        'default'                => env('DOCTRINE_CACHE', 'redis'),
        'namespace'              => "doctrine_cache",
        'second_level'           => false,
    ],
    /*
    |--------------------------------------------------------------------------
    | Gedmo extensions
    |--------------------------------------------------------------------------
    |
    | Settings for Gedmo extensions
    | If you want to use this you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'gedmo'                     => [
        'all_mappings' => true
    ]
];
