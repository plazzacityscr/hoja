#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Database CLI Tool
 * -----------------
 * Command line interface for database operations
 * 
 * Usage:
 *   php db.php migrate          - Run all pending migrations
 *   php db.php migrate:rollback - Rollback last batch of migrations
 *   php db.php migrate:status   - Show migration status
 *   php db.php seed             - Run all seeders
 *   php db.php seed:users       - Run specific seeder
 */

require __DIR__ . '/../vendor/autoload.php';

// Load environment
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envVars = parse_ini_file($envFile);
    if (is_array($envVars)) {
        foreach ($envVars as $key => $value) {
            if (getenv($key) === false) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
}

use Database\Migrator;
use Database\Seeder;

$command = $argv[1] ?? 'help';
$arg = $argv[2] ?? null;

echo "\n🍃 Leaf PHP Database CLI\n";
echo str_repeat('=', 40) . "\n\n";

try {
    switch ($command) {
        case 'migrate':
            echo "Running migrations...\n\n";
            $migrator = new Migrator();
            $migrator->run('up', $arg);
            break;

        case 'migrate:rollback':
            echo "Rolling back migrations...\n\n";
            $migrator = new Migrator();
            $migrator->run('down', $arg ?? 1);
            break;

        case 'migrate:status':
            echo "Migration Status:\n";
            echo str_repeat('-', 40) . "\n";
            $migrator = new Migrator();
            $status = $migrator->status();
            
            if (empty($status)) {
                echo "No migrations found.\n";
            } else {
                foreach ($status as $migration) {
                    $icon = $migration['status'] === 'ran' ? '✓' : '○';
                    $color = $migration['status'] === 'ran' ? 'green' : 'yellow';
                    echo "{$icon} {$migration['migration']}\n";
                }
            }
            break;

        case 'seed':
            echo "Running seeders...\n\n";
            $seeder = new Seeder();
            $seeder->run($arg);
            break;

        case 'seed:users':
            echo "Seeding users...\n\n";
            require_once __DIR__ . '/../database/seeders/UserSeeder.php';
            $seeder = new Database\Seeders\UserSeeder();
            $seeder->run();
            break;

        case 'fresh':
            echo "Fresh migration (rollback all + migrate)...\n\n";
            $migrator = new Migrator();
            echo "Rolling back...\n";
            $migrator->run('down');
            echo "\nMigrating...\n";
            $migrator->run('up');
            break;

        case 'fresh:seed':
            echo "Fresh migration with seeding...\n\n";
            $migrator = new Migrator();
            echo "Rolling back...\n";
            $migrator->run('down');
            echo "\nMigrating...\n";
            $migrator->run('up');
            echo "\nSeeding...\n";
            $seeder = new Seeder();
            $seeder->run();
            break;

        case 'help':
        default:
            echo "Available commands:\n\n";
            echo "  migrate           Run all pending migrations\n";
            echo "  migrate:rollback  Rollback last batch (or use: migrate:rollback <count>)\n";
            echo "  migrate:status    Show migration status\n";
            echo "  seed              Run all seeders\n";
            echo "  seed:users        Run user seeder\n";
            echo "  fresh             Rollback all and migrate again\n";
            echo "  fresh:seed        Fresh migration + run seeders\n";
            echo "\n";
            break;
    }

    echo "\n" . str_repeat('=', 40) . "\n";
    echo "Done.\n\n";

} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    exit(1);
}
