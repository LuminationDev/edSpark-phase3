<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            'url' => env('APP_URL') . '/storage',
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'path' => env('APP_URL') . '/storage/app/public',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
        'azure' => [
            'driver' => 'azure',
            'name' => env('AZURE_STORAGE_NAME'),
            'key' => env('AZURE_STORAGE_KEY'),
            'container' => env('AZURE_STORAGE_CONTAINER'),
            'url' => env('AZURE_STORAGE_URL'),
            'prefix' => env('AZURE_STORAGE_PREFIX'),
            'endpoint' => env("AZURE_STORAGE_ENDPOINT"),
            'path' => env("AZURE_STORAGE_PATH"),
            'retry' => [
                'tries' => 3,
                'interval' => 500,
                'increase' => 'exponential'
            ],
        ],
        'azure_catalogue' => [
            'driver' => 'azure',
            'name' => env('AZURE_STORAGE_NAME'),
            'key' => env('AZURE_STORAGE_KEY'),
            'container' => env('AZURE_STORAGE_CONTAINER'),
            'url' => env('AZURE_STORAGE_URL'),
            'prefix' => env('AZURE_STORAGE_PREFIX'),
            'endpoint' => env("AZURE_STORAGE_ENDPOINT"),
            'path' => env("AZURE_STORAGE_PATH"),
            'retry' => [
                'tries' => 3,
                'interval' => 500,
                'increase' => 'exponential'
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
