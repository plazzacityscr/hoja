<?php

declare(strict_types=1);

namespace Database\Migrations;

use Database\Migrations\Migration;

/**
 * Create Projects Table
 * ---------------------
 * Tabla para almacenar proyectos de inmuebles
 */
class CreateProjectsTable extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS projects (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                property_data JSONB,
                status VARCHAR(50) DEFAULT 'draft',
                analysis_status VARCHAR(50) DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_projects_user
                    FOREIGN KEY (user_id)
                    REFERENCES users(id)
                    ON DELETE CASCADE
            )
        ");

        // Índices para mejorar rendimiento
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_projects_user_id ON projects(user_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_projects_status ON projects(status)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_projects_analysis_status ON projects(analysis_status)");
    }

    public function down(): void
    {
        $this->db->query("DROP TABLE IF EXISTS projects");
    }
}
