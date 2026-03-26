<?php

declare(strict_types=1);

namespace Database;

/**
 * Database Seeder
 * ---------------
 * Base class for seeding database with test data
 */
class Seeder
{
    protected $db;

    public function __construct()
    {
        $this->db = \db();
    }

    /**
     * Run seeders
     */
    public function run(?string $class = null): void
    {
        $seedersPath = __DIR__ . '/../../database/seeders';

        if ($class !== null) {
            $this->runSingleSeeder($seedersPath, $class);

            return;
        }

        $files = glob($seedersPath . '/*.php');

        foreach ($files as $file) {
            require_once $file;

            $className = 'Database\\Seeders\\' . basename($file, '.php');

            if (class_exists($className)) {
                $instance = new $className();
                $instance->run();
                echo "✓ Seeded: {$className}\n";
            }
        }

        echo "Seeding completed.\n";
    }

    /**
     * Run a single seeder
     */
    protected function runSingleSeeder(string $path, string $class): void
    {
        $file = $path . '/' . $class . '.php';

        if (!file_exists($file)) {
            echo "Seeder not found: {$class}\n";

            return;
        }

        require_once $file;

        $className = 'Database\\Seeders\\' . $class;

        if (class_exists($className)) {
            $instance = new $className();
            $instance->run();
            echo "✓ Seeded: {$className}\n";
        }
    }
}
