<?php

declare(strict_types=1);

namespace App\FileStorage;

/**
 * Report Storage
 * --------------
 * Gestión de almacenamiento de informes de análisis
 * Usa PHP nativo para operaciones de file system
 */
class ReportStorage
{
    private string $reportsPath;
    private string $projectsPath;

    public function __construct()
    {
        $this->reportsPath = storage_path('reports');
        $this->projectsPath = $this->reportsPath . '/projects';

        $this->ensureDirectoriesExist();
    }

    /**
     * Asegurar que los directorios existan
     */
    private function ensureDirectoriesExist(): void
    {
        if (!is_dir($this->reportsPath)) {
            mkdir($this->reportsPath, 0755, true);
        }

        if (!is_dir($this->projectsPath)) {
            mkdir($this->projectsPath, 0755, true);
        }
    }

    /**
     * Generar reporte de análisis
     */
    public function generateReport(int $projectId, array $analysisResults): string
    {
        $projectPath = $this->projectsPath . '/' . $projectId;

        if (!is_dir($projectPath)) {
            mkdir($projectPath, 0755, true);
        }

        $filename = 'analysis_report_' . date('Y-m-d_H-i-s') . '.json';
        $filepath = $projectPath . '/' . $filename;

        $report = [
            'project_id' => $projectId,
            'generated_at' => date('Y-m-d H:i:s'),
            'total_steps' => count($analysisResults),
            'steps' => $analysisResults,
        ];

        file_put_contents($filepath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $filepath;
    }

    /**
     * Leer reporte de análisis
     */
    public function readReport(string $filepath): ?array
    {
        if (!file_exists($filepath)) {
            return null;
        }

        $content = file_get_contents($filepath);
        return json_decode($content, true);
    }

    /**
     * Obtener todos los reportes de un proyecto
     */
    public function getProjectReports(int $projectId): array
    {
        $projectPath = $this->projectsPath . '/' . $projectId;

        if (!is_dir($projectPath)) {
            return [];
        }

        $files = scandir($projectPath);
        $reports = [];

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                $filepath = $projectPath . '/' . $file;
                $reports[] = [
                    'filename' => $file,
                    'filepath' => $filepath,
                    'created_at' => date('Y-m-d H:i:s', filemtime($filepath)),
                    'size' => filesize($filepath),
                ];
            }
        }

        // Ordenar por fecha descendente
        usort($reports, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        return $reports;
    }

    /**
     * Eliminar reporte
     */
    public function deleteReport(string $filepath): bool
    {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }

    /**
     * Generar reporte en formato Markdown
     */
    public function generateMarkdownReport(int $projectId, array $analysisResults): string
    {
        $projectPath = $this->projectsPath . '/' . $projectId;

        if (!is_dir($projectPath)) {
            mkdir($projectPath, 0755, true);
        }

        $filename = 'analysis_report_' . date('Y-m-d_H-i-s') . '.md';
        $filepath = $projectPath . '/' . $filename;

        $markdown = $this->buildMarkdown($projectId, $analysisResults);

        file_put_contents($filepath, $markdown);

        return $filepath;
    }

    /**
     * Construir contenido Markdown del reporte
     */
    private function buildMarkdown(int $projectId, array $analysisResults): string
    {
        $markdown = "# Reporte de Análisis de Inmueble\n\n";
        $markdown .= "**ID del Proyecto**: {$projectId}\n";
        $markdown .= "**Fecha de Generación**: " . date('Y-m-d H:i:s') . "\n";
        $markdown .= "**Total de Pasos**: " . count($analysisResults) . "\n\n";
        $markdown .= "---\n\n";

        foreach ($analysisResults as $step) {
            $markdown .= "## Paso {$step['step_number']}: {$step['step_name']}\n\n";

            if ($step['result']['success']) {
                $markdown .= "```json\n";
                $markdown .= json_encode($step['result']['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $markdown .= "\n```\n\n";
            } else {
                $markdown .= "**Error**: {$step['result']['error']}\n\n";
            }
        }

        return $markdown;
    }
}
