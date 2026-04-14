<?php

declare(strict_types=1);

namespace Database\Migrations;

use Leaf\Db;

/**
 * Create Users Table Migration
 * ----------------------------
 * Migration: 2024_01_01_000001_create_users_table
 */
class CreateUsersTable
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
            CREATE TABLE users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Create index on email for faster lookups
        $this->db->query("CREATE INDEX users_email_index ON users (email)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->db->query("DROP TABLE IF EXISTS users");
    }
}
