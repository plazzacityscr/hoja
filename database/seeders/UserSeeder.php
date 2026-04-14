<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeder;

/**
 * User Seeder
 * -----------
 * Seed the users table with test data
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users
        $this->db->query("DELETE FROM users");

        // Insert admin user
        $this->db->query(
            "INSERT INTO users (name, email, password, email_verified_at, created_at) 
             VALUES (?, ?, ?, ?, ?)",
            [
                'Admin User',
                'admin@example.com',
                password_hash('password123', PASSWORD_DEFAULT),
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
            ]
        );

        // Insert test users
        $testUsers = [
            ['John Doe', 'john@example.com'],
            ['Jane Smith', 'jane@example.com'],
            ['Bob Wilson', 'bob@example.com'],
        ];

        foreach ($testUsers as $user) {
            $this->db->query(
                "INSERT INTO users (name, email, password, email_verified_at, created_at) 
                 VALUES (?, ?, ?, ?, ?)",
                [
                    $user[0],
                    $user[1],
                    password_hash('password123', PASSWORD_DEFAULT),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                ]
            );
        }

        echo "  → 4 users seeded\n";
    }
}
