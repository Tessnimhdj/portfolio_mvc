<?php
namespace components\Auth\Models;

use Services\Storage\JsonStorage;
use Services\Security\PasswordHasher;

class AdminModel {
    private $storage;
    private $file = 'admin.json';
    
    public function __construct() {
        $this->storage = new JsonStorage();
    }
    
    public function findByEmail(string $email): ?array {
        $admins = $this->storage->read($this->file);
        foreach ($admins as $admin) {
            if ($admin['email'] === $email) {
                return $admin;
            }
        }
        return null;
    }
    
    public function verifyCredentials(string $email, string $password): ?array {
        $admin = $this->findByEmail($email);
        if (!$admin) return null;
        if (!PasswordHasher::verify($password, $admin['password'])) return null;
        return $admin;
    }
}
