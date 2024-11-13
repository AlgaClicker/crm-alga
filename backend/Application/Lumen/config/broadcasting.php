<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "redis", "log", "null"
    |
    */

    'default' => 'redis',

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => '0ca4055ddebb8b52',
            'secret' =>'7e16b609b1f40449c547d36d7ed19557',
            'app_id' => '0ca4055ddebb8b52',
            'options' => [
                'cluster' => 'apps',
                'encrypted' => false,
                'host' =>'websockets',
                'port' => '6001'
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'socket',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
