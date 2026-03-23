<?php

declare(strict_types=1);

/**
 * Leaf PHP Application Entry Point
 * --------------------------------
 * All requests are handled by this file
 */

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load database helpers
if (file_exists(__DIR__ . '/../src/Database/Helpers.php')) {
    require __DIR__ . '/../src/Database/Helpers.php';
}

// Load configuration helpers
$configDir = __DIR__ . '/../config';
if (is_dir($configDir)) {
    foreach (glob($configDir . '/*.php') as $configFile) {
        $configName = basename($configFile, '.php');
        $config = require $configFile;
        if (is_array($config)) {
            app()->config($configName, $config);
        }
    }
}

// Load environment variables from .env file (if exists)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envVars = parse_ini_file($envFile);
    if (is_array($envVars)) {
        foreach ($envVars as $key => $value) {
            if (getenv($key) === false) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
}

// Initialize application with configuration
app()->config([
    'debug' => _env('APP_DEBUG', true),
    'mode' => _env('APP_ENV', 'development'),
    'log.enabled' => _env('LOG_DRIVER', 'file') !== 'null',
    'log.level' => _env('LOG_LEVEL', 'debug'),
    'log.dir' => _env('LOG_PATH', __DIR__ . '/../storage/logs'),
    'views.path' => __DIR__ . '/../views',
    'views.cachePath' => _env('CACHE_PATH', __DIR__ . '/../cache'),
]);

// Set custom error handler for production
if (_env('APP_ENV', 'development') === 'production' && !_env('APP_DEBUG', false)) {
    app()->setErrorHandler(function ($exception) {
        response()->view('errors/500', ['exception' => $exception], 500);
    });
}

// ===========================================
// Load Routes
// ===========================================
$routeDir = __DIR__ . '/../routes';
if (is_dir($routeDir)) {
    foreach (glob($routeDir . '/*.php') as $routeFile) {
        require $routeFile;
    }
}

// ===========================================
// Application Routes (Inline examples)
// ===========================================

// Health check endpoint (for Railway and monitoring)
app()->get('/health', function () {
    response()->json([
        'status' => 'healthy',
        'timestamp' => date('c'),
        'environment' => _env('APP_ENV', 'development'),
    ]);
});

// 404 Handler
app()->set404(function () {
    response()->json([
        'error' => 'Not Found',
        'message' => 'The requested resource was not found',
        'path' => request()->getRequestUri(),
    ], 404);
});

// ===========================================
// Run Application
// ===========================================
app()->run();
