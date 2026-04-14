<?php

declare(strict_types=1);

namespace App\Models;

use Leaf\Db;

/**
 * AnalysisResult Model
 * --------------------
 * Gestión de resultados de análisis de inmuebles
 */
class AnalysisResult
{
    protected Db $db;
    protected string $table = 'analysis_results';

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Guardar resultado de análisis
     */
    public function create(array $data): int
    {
        $this->db->query(
            "INSERT INTO {$this->table}
             (project_id, step_number, step_name, result_data, created_at)
             VALUES (?, ?, ?, ?, ?)",
            [
                $data['project_id'],
                $data['step_number'],
                $data['step_name'],
                json_encode($data['result_data']),
                date('Y-m-d H:i:s'),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * Obtener todos los resultados de un proyecto
     */
    public function getByProjectId(int $projectId): array
    {
        $results = $this->db->query(
            "SELECT * FROM {$this->table}
             WHERE project_id = ?
             ORDER BY step_number ASC",
            [$projectId]
        );

        foreach ($results as &$result) {
            $result['result_data'] = json_decode($result['result_data'], true);
        }

        return $results ?: [];
    }

    /**
     * Eliminar resultados de un proyecto
     */
    public function deleteByProjectId(int $projectId): bool
    {
        $this->db->query(
            "DELETE FROM {$this->table} WHERE project_id = ?",
            [$projectId]
        );

        return true;
    }

    /**
     * Obtener último resultado de un proyecto
     */
    public function getLatestByProjectId(int $projectId): ?array
    {
        $result = $this->db->query(
            "SELECT * FROM {$this->table}
             WHERE project_id = ?
             ORDER BY step_number DESC
             LIMIT 1",
            [$projectId]
        );

        if (isset($result[0])) {
            $result[0]['result_data'] = json_decode($result[0]['result_data'], true);
            return $result[0];
        }

        return null;
    }
}
