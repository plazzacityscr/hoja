<?php

declare(strict_types=1);

/**
 * Web Routes
 * ----------
 * Register your web routes here
 */

use App\Controllers\HomeController;

// Home page - Gentelella Dashboard
app()->get('/', [HomeController::class, 'showView']);

// Legacy view example
app()->get('/view', function () {
    response()->view('index.view', [
        'title' => 'Home - Leaf PHP',
    ]);
});

// API info
app()->get('/api/info', function () {
    response()->json([
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Built-in Server',
        'app_environment' => _env('APP_ENV', 'development'),
        'app_debug' => _env('APP_DEBUG', false),
    ]);
});

// Blade test route
app()->get('/test-blade', function () {
    return view('test', [
        'title' => 'Prueba de Blade',
        'message' => 'Blade está funcionando correctamente',
        'date' => date('Y-m-d H:i:s'),
    ]);
});
