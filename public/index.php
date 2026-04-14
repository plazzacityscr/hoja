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

// Load general functions
if (file_exists(__DIR__ . '/../src/functions.php')) {
    require __DIR__ . '/../src/functions.php';
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

// ===========================================
// Configure Session Handler
// ===========================================
$sessionConfig = app()->config('session');
if ($sessionConfig && is_array($sessionConfig)) {
    $sessionDriver = $sessionConfig['driver'] ?? 'file';
    $sessionLifetime = $sessionConfig['lifetime'] ?? 120;
    $sessionName = $sessionConfig['name'] ?? 'leaf_session';
    $sessionPath = $sessionConfig['path'] ?? '/tmp/sessions';
    $cookieConfig = $sessionConfig['cookie'] ?? [];

    // Configurar parámetros de sesión
    ini_set('session.gc_maxlifetime', $sessionLifetime * 60);
    ini_set('session.cookie_lifetime', $sessionLifetime * 60);
    ini_set('session.name', $sessionName);

    // Configurar cookie de sesión
    $cookieParams = [
        'lifetime' => $sessionLifetime * 60,
        'path' => '/',
        'domain' => '',
        'secure' => $cookieConfig['secure'] ?? true,
        'httponly' => $cookieConfig['http_only'] ?? true,
        'samesite' => $cookieConfig['same_site'] ?? 'lax',
    ];
    session_set_cookie_params($cookieParams);

    // Configurar handler de Redis si está disponible
    if ($sessionDriver === 'redis' && extension_loaded('redis')) {
        $redisUrl = _env('REDIS_URL');
        if ($redisUrl) {
            $parsed = parse_url($redisUrl);
            $redisHost = $parsed['host'] ?? '127.0.0.1';
            $redisPort = $parsed['port'] ?? 6379;
            $redisPassword = $parsed['pass'] ?? null;
            $redisDatabase = isset($parsed['path']) ? (int) ltrim($parsed['path'], '/') : 0;

            $redis = new Redis();
            $connected = $redis->connect($redisHost, $redisPort, 5);

            if ($connected) {
                if ($redisPassword) {
                    $redis->auth($redisPassword);
                }
                if ($redisDatabase > 0) {
                    $redis->select($redisDatabase);
                }

                // Configurar Redis como handler de sesión
                ini_set('session.save_handler', 'redis');
                ini_set('session.save_path', "tcp://$redisHost:$redisPort?database=$redisDatabase" . ($redisPassword ? '&auth=' . rawurlencode($redisPassword) : ''));

                $redis->close();
            } else {
                // Fallback a file si Redis no está disponible
                ini_set('session.save_handler', 'files');
                ini_set('session.save_path', $sessionPath);
            }
        } else {
            // Fallback a file si no hay REDIS_URL
            ini_set('session.save_handler', 'files');
            ini_set('session.save_path', $sessionPath);
        }
    } else {
        // Usar handler de archivos por defecto
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', $sessionPath);
    }
}

// Initialize Blade view engine
$viewConfig = app()->config('view');
if ($viewConfig && is_array($viewConfig)) {
    $blade = new \Leaf\Blade(
        $viewConfig['views_path'] ?? __DIR__ . '/../views',
        $viewConfig['cache_path'] ?? __DIR__ . '/../storage/cache/views'
    );
    app()->blade($blade);
}

// Set custom error handler for production
if (_env('APP_ENV', 'development') === 'production' && !_env('APP_DEBUG', false)) {
    app()->setErrorHandler(function ($exception) {
        response()->view('errors/500', ['exception' => $exception], 500);
    });
}

// ===========================================
// Load Routes
// ===========================================
// Note: routes/web.php is temporarily disabled due to routing issues
// All routes are defined inline to ensure proper execution order

// ===========================================
// Application Routes
// ===========================================

// Health check endpoint (for Railway and monitoring)
app()->get('/health', function () {
    response()->json([
        'status' => 'healthy',
        'timestamp' => date('c'),
        'environment' => _env('APP_ENV', 'development'),
    ]);
});

// Home page route (alternative to /)
app()->get('/home', function () {
    echo "Home route is working";
});

// Dashboard route
app()->get('/dashboard', function () {
    echo "Dashboard route is working";
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
// Serve static files if they exist
// ===========================================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

// Check if the requested URI corresponds to a static file
if (file_exists($filePath) && !is_dir($filePath)) {
    // Determine the content type based on file extension
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];
    
    $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';
    
    header("Content-Type: $contentType");
    header("Content-Length: " . filesize($filePath));
    readfile($filePath);
    exit;
}

// ===========================================
// Run Application
// ===========================================
app()->run();
