<?php

declare(strict_types=1);

/**
 * Database Configuration for leafs/db
 * ------------------------------------
 * Configure your database connections for leafs/db
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    */
    'default' => _env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => _env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => _env('DB_HOST', '127.0.0.1'),
            'port' => _env('DB_PORT', '3306'),
            'database' => _env('DB_DATABASE', 'leaf_app'),
            'username' => _env('DB_USERNAME', 'root'),
            'password' => _env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => _env('DB_HOST', '127.0.0.1'),
            'port' => _env('DB_PORT', '5432'),
            'database' => _env('DB_DATABASE', 'leaf_app'),
            'username' => _env('DB_USERNAME', 'root'),
            'password' => _env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    */
    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'client' => _env('REDIS_CLIENT', 'phpredis'),
        'default' => [
            'host' => _env('REDIS_HOST', '127.0.0.1'),
            'password' => _env('REDIS_PASSWORD'),
            'port' => _env('REDIS_PORT', '6379'),
            'database' => _env('REDIS_DB', '0'),
        ],
    ],
];
