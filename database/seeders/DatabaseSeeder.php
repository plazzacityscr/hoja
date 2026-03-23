<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeder;

/**
 * Database Seeder
 * ---------------
 * Main seeder class that runs all seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "Seeding database...\n";
        
        $this->call(UserSeeder::class);
        
        // Add more seeders here as needed
        // $this->call(PostSeeder::class);
        // $this->call(CommentSeeder::class);
        
        echo "Database seeding completed.\n";
    }

    /**
     * Call a specific seeder
     */
    protected function call(string $class): void
    {
        $instance = new $class();
        $instance->run();
    }
}
