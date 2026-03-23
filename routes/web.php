<?php

declare(strict_types=1);

/**
 * Web Routes
 * ----------
 * Register your web routes here
 */

// Home page
app()->get('/', function () {
    response()->json([
        'message' => 'Welcome to Leaf PHP',
        'version' => '3.0',
        'documentation' => 'https://leafphp.dev',
    ]);
});

// View example
app()->get('/view', function () {
    response()->view('index', [
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
