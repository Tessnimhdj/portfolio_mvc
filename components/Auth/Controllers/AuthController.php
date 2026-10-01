<?php
namespace components\Auth\Controllers;

use Services\Security\AuthSession;
use components\Auth\Models\AdminModel;

class AuthController {
    private $model;
    
    public function __construct() {
        $this->model = new AdminModel();
    }
    
    public function showLogin() {
        if (AuthSession::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Dashboard/index');
            exit;
        }
        $error = AuthSession::flash('error');
        require __DIR__ . '/../Views/login.php';
    }
    
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $admin = $this->model->verifyCredentials($email, $password);
        
        if ($admin) {
            AuthSession::login($admin);
            header('Location: ' . BASE_URL . '/Dashboard/index');
            exit;
        }
        
        AuthSession::flash('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة');
        header('Location: ' . BASE_URL . '/Auth/showLogin');
        exit;
    }
    
    public function logout() {
        AuthSession::logout();
        header('Location: ' . BASE_URL . '/Auth/showLogin');
        exit;
    }
}