<?php

declare(strict_types=1);

namespace App\Middleware;

/**
 * Example Middleware
 * ------------------
 * Middleware runs before/after route handlers
 */
class ExampleMiddleware
{
    /**
     * Handle the middleware call
     */
    public function call()
    {
        // Code here runs BEFORE the route handler
        
        // Example: Add a header to all responses
        // header('X-Custom-Header: Leaf PHP');
        
        // Example: Check authentication
        // if (!$this->isAuthenticated()) {
        //     response()->json(['error' => 'Unauthorized'], 401);
        //     exit;
        // }
        
        // Continue to route handler
        // After route handler completes, code below runs
        
        // Example: Log request
        // error_log("Request to " . request()->getRequestUri());
    }

    /**
     * Example authentication check
     */
    private function isAuthenticated(): bool
    {
        // Implement your authentication logic here
        return true;
    }
}
