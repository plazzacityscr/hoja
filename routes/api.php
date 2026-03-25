<?php

declare(strict_types=1);

/**
 * API Routes
 * ----------
 * Register your API routes here
 */

use App\Models\User;

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

    // User resources (CRUD) - Direct implementation
    app()->get('/users', function () {
        try {
            $user = new User();
            $users = $user->all();
            response()->json([
                'success' => true,
                'data' => $users,
                'count' => count($users),
            ]);
        } catch (Exception $e) {
            response()->json([
                'success' => false,
                'error' => 'Database not configured. Run: php db.php migrate',
                'details' => $e->getMessage(),
            ], 500);
        }
    });
    
    app()->get('/users/{id}', function ($id) {
        try {
            $user = new User();
            $userData = $user->find((int)$id);
            
            if (!$userData) {
                response()->json([
                    'success' => false,
                    'error' => 'User not found',
                ], 404);
                return;
            }
            
            response()->json([
                'success' => true,
                'data' => $userData,
            ]);
        } catch (Exception $e) {
            response()->json([
                'success' => false,
                'error' => 'Database not configured',
                'details' => $e->getMessage(),
            ], 500);
        }
    });
    
    app()->post('/users', function () {
        try {
            $user = new User();
            $data = request()->json()->all();
            
            // Validation
            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                response()->json([
                    'success' => false,
                    'error' => 'Name, email and password are required',
                ], 400);
                return;
            }
            
            $userId = $user->create($data);
            
            response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => ['id' => $userId],
            ], 201);
        } catch (Exception $e) {
            response()->json([
                'success' => false,
                'error' => 'Database not configured',
                'details' => $e->getMessage(),
            ], 500);
        }
    });
    
    app()->put('/users/{id}', function ($id) {
        try {
            $user = new User();
            $data = request()->json()->all();
            
            $user->update((int)$id, $data);
            
            response()->json([
                'success' => true,
                'message' => 'User updated successfully',
            ]);
        } catch (Exception $e) {
            response()->json([
                'success' => false,
                'error' => 'Database not configured',
                'details' => $e->getMessage(),
            ], 500);
        }
    });
    
    app()->delete('/users/{id}', function ($id) {
        try {
            $user = new User();
            $user->delete((int)$id);
            
            response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (Exception $e) {
            response()->json([
                'success' => false,
                'error' => 'Database not configured',
                'details' => $e->getMessage(),
            ], 500);
        }
    });
});
