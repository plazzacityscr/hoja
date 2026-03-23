<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

/**
 * User Controller
 * ---------------
 * Handles user-related operations
 */
class UserController
{
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Display all users
     */
    public function index()
    {
        $users = $this->userModel->all();
        
        response()->json([
            'success' => true,
            'data' => $users,
            'count' => count($users),
        ]);
    }

    /**
     * Display a specific user
     */
    public function show(int $id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            response()->json([
                'success' => false,
                'error' => 'User not found',
            ], 404);
            return;
        }

        response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Store a new user
     */
    public function store()
    {
        $data = request()->json()->all();
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Name is required';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            response()->json([
                'success' => false,
                'errors' => $errors,
            ], 400);
            return;
        }

        // Check if email exists
        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) {
            response()->json([
                'success' => false,
                'error' => 'Email already exists',
            ], 400);
            return;
        }

        // Create user
        $userId = $this->userModel->create($data);

        response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => ['id' => $userId],
        ], 201);
    }

    /**
     * Update a user
     */
    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            response()->json([
                'success' => false,
                'error' => 'User not found',
            ], 404);
            return;
        }

        $data = request()->json()->all();
        
        // Update user
        $this->userModel->update($id, $data);

        response()->json([
            'success' => true,
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Delete a user
     */
    public function destroy(int $id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            response()->json([
                'success' => false,
                'error' => 'User not found',
            ], 404);
            return;
        }

        $this->userModel->delete($id);

        response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
