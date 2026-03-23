<?php

declare(strict_types=1);

/**
 * API Routes
 * ----------
 * Register your API routes here
 */

use App\Controllers\UserController;

// API versioning prefix
app()->group('/api/v1', function () {
    // Public endpoints
    app()->get('/health', function () {
        response()->json([
            'status' => 'healthy',
            'timestamp' => date('c'),
            'version' => '1.0.0',
        ]);
    });

    app()->get('/info', function () {
        response()->json([
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Built-in Server',
            'app_environment' => _env('APP_ENV', 'development'),
            'app_debug' => _env('APP_DEBUG', false),
        ]);
    });

    // Echo endpoint for testing
    app()->post('/echo', function () {
        $input = request()->json()->all();
        response()->json([
            'received' => $input,
            'method' => request()->getMethod(),
            'headers' => request()->headers()->all(),
        ]);
    });

    // User resources (CRUD) - using closures to call controller methods
    app()->get('/users', function () {
        $controller = new UserController();
        $controller->index();
    });
    
    app()->get('/users/{id}', function ($id) {
        $controller = new UserController();
        $controller->show($id);
    });
    
    app()->post('/users', function () {
        $controller = new UserController();
        $controller->store();
    });
    
    app()->put('/users/{id}', function ($id) {
        $controller = new UserController();
        $controller->update($id);
    });
    
    app()->delete('/users/{id}', function ($id) {
        $controller = new UserController();
        $controller->destroy($id);
    });
});
