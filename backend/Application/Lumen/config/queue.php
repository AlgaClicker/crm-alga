<?php
return [
    'default' => 'redis',

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'queue',
            'queue' => '{default}',
            'retry_after' => 90,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'pgsql'),
        'table' => env('QUEUE_FAILED_TABLE', 'failed_jobs'),
    ],

];
