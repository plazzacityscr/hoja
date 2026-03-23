<?php

declare(strict_types=1);

/**
 * Database Helper Functions
 * -------------------------
 * Global helpers for database operations with leafs/db
 */

use Leaf\Db;

if (!function_exists('db')) {
    /**
     * Get a database connection instance
     *
     * @param string|null $connection
     * @return \Leaf\Db
     */
    function db(?string $connection = null): Db
    {
        $config = config('database');
        $connection = $connection ?? $config['default'];
        
        return new Db($config['connections'][$connection]);
    }
}

if (!function_exists('database_path')) {
    /**
     * Get the database path
     *
     * @param string $path
     * @return string
     */
    function database_path(string $path = ''): string
    {
        $base = __DIR__ . '/../storage/database';
        return $path ? $base . '/' . $path : $base;
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        static $configs = [];
        
        $parts = explode('.', $key);
        $file = $parts[0];
        
        if (!isset($configs[$file])) {
            $configFile = __DIR__ . '/../config/' . $file . '.php';
            if (file_exists($configFile)) {
                $configs[$file] = require $configFile;
            } else {
                return $default;
            }
        }
        
        $value = $configs[$file];
        for ($i = 1; $i < count($parts); $i++) {
            if (isset($value[$parts[$i]])) {
                $value = $value[$parts[$i]];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
}

if (!function_exists('migrate')) {
    /**
     * Run database migrations
     *
     * @param string $direction
     * @return void
     */
    function migrate(string $direction = 'up'): void
    {
        $migrator = new Database\Migrator();
        $migrator->run($direction);
    }
}

if (!function_exists('seed')) {
    /**
     * Run database seeders
     *
     * @param string|null $class
     * @return void
     */
    function seed(?string $class = null): void
    {
        $seeder = new Database\Seeder();
        $seeder->run($class);
    }
}
