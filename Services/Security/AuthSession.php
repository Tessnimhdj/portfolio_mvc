<?php
namespace Services\Security;

class AuthSession {
    
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function login(array $admin): void {
        self::start();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_name'] = $admin['name'] ?? 'Admin';
    }
    
    public static function logout(): void {
        self::start();
        session_destroy();
    }
    
    public static function isLoggedIn(): bool {
        self::start();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    public static function getAdmin(): ?array {
        self::start();
        if (!self::isLoggedIn()) return null;
        return [
            'id' => $_SESSION['admin_id'] ?? null,
            'email' => $_SESSION['admin_email'] ?? null,
            'name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
    }
    
    public static function requireAuth(): void {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Auth/showLogin');
            exit;
        }
    }
    
    public static function flash(string $key, $value = null) {
        self::start();
        if ($value === null) {
            $val = $_SESSION[$key] ?? null;
            unset($_SESSION[$key]);
            return $val;
        }
        $_SESSION[$key] = $value;
    }
}
