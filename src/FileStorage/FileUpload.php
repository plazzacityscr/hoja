<?php

declare(strict_types=1);

namespace App\FileStorage;

/**
 * File Upload Handler
 * ------------------
 * Gestión de subida de archivos
 * Usa PHP nativo para operaciones de file system
 */
class FileUpload
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath = storage_path('uploads');

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    /**
     * Subir archivo
     */
    public function upload(array $file, string $subdirectory = ''): array
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Invalid file upload');
        }

        $targetPath = $this->uploadPath;

        if ($subdirectory) {
            $targetPath .= '/' . $subdirectory;
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
        }

        $filename = $this->generateUniqueFilename($file['name']);
        $filepath = $targetPath . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new \Exception('Failed to move uploaded file');
        }

        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'original_name' => $file['name'],
            'size' => filesize($filepath),
            'mime_type' => $file['type'],
        ];
    }

    /**
     * Generar nombre de archivo único
     */
    private function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $basename);

        return $basename . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Validar tipo de archivo
     */
    public function validateFileType(array $file, array $allowedTypes): bool
    {
        $fileType = $file['type'];
        return in_array($fileType, $allowedTypes);
    }

    /**
     * Validar tamaño de archivo
     */
    public function validateFileSize(array $file, int $maxSizeBytes): bool
    {
        return $file['size'] <= $maxSizeBytes;
    }
}
