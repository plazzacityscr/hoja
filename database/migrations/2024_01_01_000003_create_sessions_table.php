<?php

declare(strict_types=1);

namespace Database\Migrations;

use Leaf\Db;

/**
 * Create Sessions Table Migration
 * --------------------------------
 * Migration: 2024_01_01_000003_create_sessions_table
 */
class CreateSessionsTable
{
    protected Db $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE sessions (
                id VARCHAR(255) NOT NULL PRIMARY KEY,
                user_id INT UNSIGNED NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload LONGTEXT NOT NULL,
                last_activity INT NOT NULL,
                INDEX sessions_user_id_index (user_id),
                INDEX sessions_last_activity_index (last_activity)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->db->query("DROP TABLE IF EXISTS sessions");
    }
}
