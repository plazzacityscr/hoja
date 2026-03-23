<?php

declare(strict_types=1);

namespace App\Models;

use Leaf\Db;

/**
 * User Model
 * ----------
 * Base model for user operations using leafs/db
 */
class User
{
    protected Db $db;
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password'];
    protected array $hidden = ['password', 'remember_token'];

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Get all users
     */
    public function all(): array
    {
        $users = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $this->hideFields($users);
    }

    /**
     * Find a user by ID
     */
    public function find(int $id): ?array
    {
        $user = $this->db->query(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );
        
        return isset($user[0]) ? $this->hideFields([$user[0]])[0] : null;
    }

    /**
     * Find a user by email
     */
    public function findByEmail(string $email): ?array
    {
        $user = $this->db->query(
            "SELECT * FROM {$this->table} WHERE email = ?",
            [$email]
        );
        
        return isset($user[0]) ? $this->hideFields([$user[0]])[0] : null;
    }

    /**
     * Create a new user
     */
    public function create(array $data): int
    {
        $this->db->query(
            "INSERT INTO {$this->table} (name, email, password, created_at) 
             VALUES (?, ?, ?, ?)",
            [
                $data['name'],
                $data['email'],
                $this->hashPassword($data['password']),
                date('Y-m-d H:i:s'),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * Update a user
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                if ($key === 'password') {
                    $value = $this->hashPassword($value);
                }
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . ", updated_at = ? WHERE id = ?";
        $values[] = date('Y-m-d H:i:s');

        $this->db->query($sql, $values);
        return true;
    }

    /**
     * Delete a user
     */
    public function delete(int $id): bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
        return $this->db->affectedRows() > 0;
    }

    /**
     * Soft delete a user
     */
    public function softDelete(int $id): bool
    {
        $this->db->query(
            "UPDATE {$this->table} SET deleted_at = ? WHERE id = ?",
            [date('Y-m-d H:i:s'), $id]
        );
        return true;
    }

    /**
     * Find a user including soft deleted
     */
    public function withTrashed(): self
    {
        // For simplicity, just return self (full implementation would modify queries)
        return $this;
    }

    /**
     * Hash a password
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify a password
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Hide sensitive fields from results
     */
    protected function hideFields(array $users): array
    {
        return array_map(function ($user) {
            foreach ($this->hidden as $field) {
                unset($user[$field]);
            }
            return $user;
        }, $users);
    }

    /**
     * Get user count
     */
    public function count(): int
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
        return (int) ($result[0]['count'] ?? 0);
    }
}
