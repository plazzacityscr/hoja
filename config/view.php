<?php

return [
    /**
     * Directorio de vistas
     * @var string
     */
    'views_path' => app()->path('views'),

    /**
     * Directorio de caché para vistas compiladas
     * @var string
     */
    'cache_path' => app()->path('storage/cache/views'),

    /**
     * Extensión de archivos de vista
     * @var string
     */
    'extension' => '.blade.php',

    /**
     * Compilar en modo debug
     * @var bool
     */
    'compile' => env('APP_DEBUG', true),
];
