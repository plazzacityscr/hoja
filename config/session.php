<?php

declare(strict_types=1);

/**
 * Session Configuration
 * ---------------------
 * Configuración de sesiones para la aplicación Leaf
 * Soporta drivers: redis, file, database
 */

/**
 * @return array{
 *     driver: string,
 *     lifetime: int,
 *     name: string,
 *     path: string,
 *     cookie: array{
 *         secure: bool,
 *         http_only: bool,
 *         same_site: string
 *     }
 * }
 */
return [
    // Driver de sesiones: redis, file, database
    'driver' => _env('SESSION_DRIVER', 'file'),

    // Duración de sesión en minutos
    'lifetime' => (int) _env('SESSION_LIFETIME', 120),

    // Nombre de la cookie de sesión
    'name' => _env('SESSION_NAME', 'leaf_session'),

    // Path de almacenamiento (para driver file)
    'path' => _env('SESSION_PATH', '/tmp/sessions'),

    // Configuración de cookie
    'cookie' => [
        'secure' => _env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'lax',
    ],
];
