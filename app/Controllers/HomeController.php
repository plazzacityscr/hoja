<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Home Controller
 * ---------------
 * Example controller for home routes
 */
class HomeController
{
    /**
     * Display the home page
     */
    public function index()
    {
        response()->json([
            'message' => 'Welcome to Leaf PHP',
            'version' => '3.0',
            'documentation' => 'https://leafphp.dev',
        ]);
    }

    /**
     * Display a view example
     */
    public function showView()
    {
        // Diagnostic: try to return JSON first
        return response()->json([
            'message' => 'Dashboard route is working',
            'title' => 'Home - Leaf PHP',
        ]);
    }

    /**
     * API info endpoint
     */
    public function apiInfo()
    {
        response()->json([
            'api' => 'Leaf PHP API',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /' => 'Welcome message',
                'GET /health' => 'Health check',
                'GET /api/info' => 'API information',
                'POST /api/echo' => 'Echo request',
            ],
            'timestamp' => date('c'),
        ]);
    }
}
