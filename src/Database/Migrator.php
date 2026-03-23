<?php

declare(strict_types=1);

namespace Database;

/**
 * Database Migrator
 * -----------------
 * Handles running migrations up and down
 */
class Migrator
{
    protected string $migrationsPath;
    protected string $table = 'migrations';
    protected $db;

    public function __construct()
    {
        $this->migrationsPath = __DIR__ . '/../../database/migrations';
        $this->db = \db();
        $this->ensureMigrationsTableExists();
    }

    /**
     * Ensure the migrations table exists
     */
    protected function ensureMigrationsTableExists(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS {$this->table} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    /**
     * Run migrations
     */
    public function run(string $direction = 'up', ?string $step = null): void
    {
        $migrations = $this->getPendingMigrations();
        
        if ($direction === 'down') {
            $migrations = array_reverse($migrations);
        }

        $batch = $this->getNextBatchNumber();
        $ran = 0;

        foreach ($migrations as $migration) {
            if ($step !== null && $ran >= (int)$step) {
                break;
            }

            $this->runMigration($migration, $direction);
            $ran++;
        }

        echo "Migrations completed. {$ran} migration(s) processed.\n";
    }

    /**
     * Get pending migrations
     */
    protected function getPendingMigrations(): array
    {
        $files = glob($this->migrationsPath . '/*.php');
        
        if (empty($files)) {
            return [];
        }

        $ran = $this->getRanMigrations();
        $pending = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (!in_array($name, $ran)) {
                $pending[] = $name;
            }
        }

        sort($pending);
        return $pending;
    }

    /**
     * Get already ran migrations
     */
    protected function getRanMigrations(): array
    {
        $result = $this->db->query("SELECT migration FROM {$this->table} ORDER BY id");
        return array_column($result, 'migration');
    }

    /**
     * Get next batch number
     */
    protected function getNextBatchNumber(): int
    {
        $result = $this->db->query("SELECT MAX(batch) as batch FROM {$this->table}");
        return ($result[0]['batch'] ?? 0) + 1;
    }

    /**
     * Run a single migration
     */
    protected function runMigration(string $migration, string $direction): void
    {
        $file = $this->migrationsPath . '/' . $migration . '.php';
        
        if (!file_exists($file)) {
            echo "Migration file not found: {$migration}\n";
            return;
        }

        require_once $file;
        
        $className = $this->getClassName($migration);
        
        if (!class_exists($className)) {
            echo "Migration class not found: {$className}\n";
            return;
        }

        $instance = new $className();
        
        try {
            if ($direction === 'up') {
                $instance->up();
                $this->logMigration($migration);
                echo "✓ Migrated: {$migration}\n";
            } else {
                $instance->down();
                $this->removeMigration($migration);
                echo "✓ Rolled back: {$migration}\n";
            }
        } catch (\Exception $e) {
            echo "✗ Error: {$e->getMessage()}\n";
            throw $e;
        }
    }

    /**
     * Get migration class name
     */
    protected function getClassName(string $migration): string
    {
        $parts = explode('_', $migration);
        $className = implode('_', array_slice($parts, 4));
        return 'Database\\Migrations\\' . $className;
    }

    /**
     * Log a ran migration
     */
    protected function logMigration(string $migration): void
    {
        $batch = $this->getNextBatchNumber();
        $this->db->query(
            "INSERT INTO {$this->table} (migration, batch) VALUES (?, ?)",
            [$migration, $batch]
        );
    }

    /**
     * Remove a migration from log
     */
    protected function removeMigration(string $migration): void
    {
        $this->db->query("DELETE FROM {$this->table} WHERE migration = ?", [$migration]);
    }

    /**
     * Get all migrations status
     */
    public function status(): array
    {
        $files = glob($this->migrationsPath . '/*.php');
        $ran = $this->getRanMigrations();
        $status = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $status[] = [
                'migration' => $name,
                'status' => in_array($name, $ran) ? 'ran' : 'pending',
            ];
        }

        return $status;
    }
}
