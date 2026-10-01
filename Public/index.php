<?php
/**
 * Portfolio MVC - Entry Point
 */

session_start();

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', '/mes_projet/portfolio_mvc/Public');

// Load Router (handles case-sensitive folder names)
$routerPath = __DIR__ . '/../core/routing/Router.php';
if (!file_exists($routerPath)) {
    // Try with a capital first letter
    $routerPath = __DIR__ . '/../Core/routing/Router.php';
}

if (!file_exists($routerPath)) {
    die("Router file not found! Check path: $routerPath");
}

require_once $routerPath;

// Load Routes file if exists
$routesPath = __DIR__ . '/../core/routing/Routes.php';
if (!file_exists($routesPath)) {
    $routesPath = __DIR__ . '/../Core/routing/Routes.php';
}

if (file_exists($routesPath)) {
    require_once $routesPath;
}

// Autoloader for classes
spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    
    // Try relative to parent directory (project root)
    $file = __DIR__ . '/../' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Try with lowercase first letter for folders
    $parts = explode('/', $class);
    if (count($parts) > 0) {
        $parts[0] = strtolower($parts[0]);
        $file = __DIR__ . '/../' . implode('/', $parts) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Enable auto-routing
Router::enableAutoRouting(true);

// Dispatch the request
Router::dispatch();