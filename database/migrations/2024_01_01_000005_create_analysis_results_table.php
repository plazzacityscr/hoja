<?php

declare(strict_types=1);

namespace Database\Migrations;

use Database\Migrations\Migration;

/**
 * Create Analysis Results Table
 * -----------------------------
 * Tabla para almacenar resultados de análisis de inmuebles
 */
class CreateAnalysisResultsTable extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS analysis_results (
                id SERIAL PRIMARY KEY,
                project_id INTEGER NOT NULL,
                step_number INTEGER NOT NULL,
                step_name VARCHAR(255) NOT NULL,
                result_data JSONB,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_analysis_results_project
                    FOREIGN KEY (project_id)
                    REFERENCES projects(id)
                    ON DELETE CASCADE
            )
        ");

        // Índices para mejorar rendimiento
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_analysis_results_project_id ON analysis_results(project_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_analysis_results_step_number ON analysis_results(step_number)");
    }

    public function down(): void
    {
        $this->db->query("DROP TABLE IF EXISTS analysis_results");
    }
}
