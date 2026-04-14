<?php

declare(strict_types=1);

namespace App\Models;

use Leaf\Db;

/**
 * Project Model
 * -------------
 * Gestión de proyectos de inmuebles para Hoja
 */
class Project
{
    protected Db $db;
    protected string $table = 'projects';
    protected array $fillable = [
        'user_id',
        'name',
        'description',
        'property_data',
        'status',
        'analysis_status',
    ];

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Obtener todos los proyectos de un usuario
     */
    public function getByUserId(int $userId): array
    {
        $result = $this->db->query(
            "SELECT * FROM {$this->table}
             WHERE user_id = ?
             ORDER BY created_at DESC",
            [$userId]
        );

        return $result ?: [];
    }

    /**
     * Crear un nuevo proyecto
     */
    public function create(array $data): int
    {
        $this->db->query(
            "INSERT INTO {$this->table}
             (user_id, name, description, property_data, status, analysis_status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['user_id'],
                $data['name'],
                $data['description'] ?? null,
                json_encode($data['property_data']),
                $data['status'] ?? 'draft',
                $data['analysis_status'] ?? 'pending',
                date('Y-m-d H:i:s'),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * Actualizar estado de análisis
     */
    public function updateAnalysisStatus(int $projectId, string $status): bool
    {
        $this->db->query(
            "UPDATE {$this->table}
             SET analysis_status = ?, updated_at = ?
             WHERE id = ?",
            [$status, date('Y-m-d H:i:s'), $projectId]
        );

        return true;
    }

    /**
     * Actualizar proyecto
     */
    public function update(int $id, array $data): bool
    {
        $this->db->query(
            "UPDATE {$this->table}
             SET name = ?, description = ?, property_data = ?, status = ?, updated_at = ?
             WHERE id = ?",
            [
                $data['name'] ?? null,
                $data['description'] ?? null,
                json_encode($data['property_data'] ?? []),
                $data['status'] ?? 'draft',
                date('Y-m-d H:i:s'),
                $id,
            ]
        );

        return true;
    }

    /**
     * Eliminar proyecto
     */
    public function delete(int $id): bool
    {
        $this->db->query(
            "DELETE FROM {$this->table} WHERE id = ?",
            [$id]
        );

        return true;
    }

    /**
     * Buscar proyecto por ID
     */
    public function find(int $id): ?array
    {
        $project = $this->db->query(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );

        if (isset($project[0])) {
            $project[0]['property_data'] = json_decode($project[0]['property_data'], true);
            return $project[0];
        }

        return null;
    }

    /**
     * Obtener todos los proyectos
     */
    public function all(): array
    {
        $result = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        
        foreach ($result as &$project) {
            $project['property_data'] = json_decode($project['property_data'], true);
        }

        return $result ?: [];
    }
}
