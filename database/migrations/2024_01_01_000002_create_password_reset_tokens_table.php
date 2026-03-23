<?php

declare(strict_types=1);

namespace Database\Migrations;

use Leaf\Db;

/**
 * Create Password Reset Tokens Table Migration
 * ---------------------------------------------
 * Migration: 2024_01_01_000002_create_password_reset_tokens_table
 */
class CreatePasswordResetTokensTable
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
            CREATE TABLE password_reset_tokens (
                email VARCHAR(255) NOT NULL PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->db->query("DROP TABLE IF EXISTS password_reset_tokens");
    }
}
