<?php

class Router
{
    private static array $routes = [];
    private static ?string $basePath = null;
    private static bool $autoRouting = true;
    private static array $controllerCache = [];
    private static bool $cacheBuilt = false;
    private static string $defaultController = 'HomeController';

    public static function get(string $uri, $action)
    {
        $uri = '/' . ltrim($uri, '/');
        self::$routes['GET'][$uri] = $action;
    }

    public static function post(string $uri, $action)
    {
        $uri = '/' . ltrim($uri, '/');
        self::$routes['POST'][$uri] = $action;
    }

    public static function enableAutoRouting(bool $enable = true)
    {
        self::$autoRouting = $enable;
    }

    public static function setDefaultController(string $controller)
    {
        self::$defaultController = $controller;
    }

    public static function dispatch()
    {
        self::logDebug("=== NEW REQUEST ===");
        self::logDebug("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        self::logDebug("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
        
        self::$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        self::logDebug("BASE_PATH: " . self::$basePath);
        
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = self::normalizeUri($_SERVER['REQUEST_URI']);
        
        self::logDebug("NORMALIZED_URI: " . $uri);

        // Check if it's a static file request (images, css, js)
        if (self::isStaticFile($uri)) {
            self::logDebug("Static file request - skipping routing");
            return;
        }

        // First: check manually registered routes
        if (isset(self::$routes[$method][$uri])) {
            self::logDebug("Found manual route for: $uri");
            $action = self::$routes[$method][$uri];
            return self::executeAction($action);
        }

        self::logDebug("No manual route. Trying auto-routing...");
        
        // Second: automatic routing
        if (self::$autoRouting) {
            return self::autoRoute($uri, $method);
        }

        // If no route is found
        self::show404($uri);
    }

    private static function isStaticFile(string $uri): bool
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'css', 'js', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'pdf'];
        $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
        return in_array($ext, $extensions);
    }

    private static function autoRoute(string $uri, string $method)
    {
        self::logDebug("Auto-routing for URI: $uri");
        
        $uri = trim($uri, '/');
        
        if (empty($uri)) {
            $controllerName = self::$defaultController;
            $methodName = 'index';
            self::logDebug("Empty URI - using default: $controllerName::$methodName");
        } else {
            $segments = explode('/', $uri);
            self::logDebug("URI segments: " . implode(', ', $segments));
            
            $controllerName = ucfirst($segments[0]) . 'Controller';
            $methodName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] : 'index';
            $methodName = lcfirst(str_replace('-', '', ucwords($methodName, '-')));
            
            self::logDebug("Looking for: Controller=$controllerName, Method=$methodName");
        }

        $controllerInfo = self::findControllerAnywhere($controllerName);

        if (!$controllerInfo) {
            self::logDebug("FAILED: Controller not found!");
            self::show404($uri, "Controller not found: $controllerName");
            return;
        }

        $controllerFile = $controllerInfo['file'];
        $controllerClass = $controllerInfo['class'];
        
        self::logDebug("Found: Class=$controllerClass, File=$controllerFile");
        
        if (!file_exists($controllerFile)) {
            self::show404($uri, "Controller file not found: $controllerFile");
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            self::show404($uri, "Controller class not found: $controllerClass");
            return;
        }

        $controller = new $controllerClass;

        if (!method_exists($controller, $methodName)) {
            self::show404($uri, "Method '$methodName' not found in $controllerClass");
            return;
        }

        self::logDebug("SUCCESS: Executing $controllerClass::$methodName()");
        
        return $controller->$methodName();
    }

    private static function executeAction($action)
    {
        if (is_callable($action)) {
            return $action();
        }

        if (is_string($action) && strpos($action, '@') !== false) {
            list($controller, $methodName) = explode('@', $action);
            $controllerInfo = self::findControllerAnywhere($controller);

            if (!$controllerInfo) {
                throw new Exception("Controller not found: $controller");
            }
            
            $controllerClass = $controllerInfo['class'];
            $controllerFile = $controllerInfo['file'];

            if (!file_exists($controllerFile)) {
                throw new Exception("Controller file not found: $controllerFile");
            }

            require_once $controllerFile;

            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: $controllerClass");
            }

            $obj = new $controllerClass;

            if (!method_exists($obj, $methodName)) {
                throw new Exception("Method $methodName not found in $controllerClass");
            }

            return $obj->$methodName();
        }
    }

    private static function findControllerAnywhere(string $controllerName): ?array
    {
        $projectRoot = self::getProjectRoot();
        self::logDebug("Searching for $controllerName in project root: $projectRoot");
        
        if (!self::$cacheBuilt) {
            self::logDebug("Building controller cache...");
            self::buildControllerCache();
        }

        if (isset(self::$controllerCache[$controllerName])) {
            self::logDebug("Found in cache!");
            return self::$controllerCache[$controllerName];
        }

        self::logDebug("Not in cache. Doing fresh scan...");
        $result = self::scanForController($controllerName);
        
        if ($result) {
            self::$controllerCache[$controllerName] = $result;
            self::logDebug("Found at: " . $result['file']);
        } else {
            self::logDebug("NOT FOUND");
        }

        return $result;
    }

    private static function buildControllerCache(): void
    {
        $projectRoot = self::getProjectRoot();
        self::scanDirectory($projectRoot, $projectRoot);
        self::$cacheBuilt = true;
        self::logDebug("Cache built with " . count(self::$controllerCache) . " controllers");
    }

    private static function scanForController(string $controllerName): ?array
    {
        $projectRoot = self::getProjectRoot();
        return self::findInDirectory($projectRoot, $projectRoot, $controllerName);
    }

    private static function findInDirectory(string $dir, string $projectRoot, string $controllerName): ?array
    {
        if (!is_dir($dir)) {
            return null;
        }

        $skipDirs = ['vendor', 'node_modules', '.git', 'storage', 'cache', 'temp', 'Public', 'public', 'logs'];
        $dirName = basename($dir);
        
        if (in_array($dirName, $skipDirs)) {
            return null;
        }

        $items = @scandir($dir);
        if ($items === false) {
            return null;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_file($fullPath) && $item === $controllerName . '.php') {
                $namespace = self::extractNamespace($fullPath);
                $className = $namespace ? $namespace . '\\' . $controllerName : $controllerName;
                
                return [
                    'class' => $className,
                    'file' => $fullPath
                ];
            }

            if (is_dir($fullPath)) {
                $result = self::findInDirectory($fullPath, $projectRoot, $controllerName);
                if ($result) {
                    return $result;
                }
            }
        }

        return null;
    }

    private static function scanDirectory(string $dir, string $projectRoot): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $skipDirs = ['vendor', 'node_modules', '.git', 'storage', 'cache', 'temp', 'Public', 'public', 'logs'];
        $dirName = basename($dir);
        
        if (in_array($dirName, $skipDirs)) {
            return;
        }

        $items = @scandir($dir);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_file($fullPath) && str_ends_with($item, 'Controller.php')) {
                $controllerName = basename($item, '.php');
                $namespace = self::extractNamespace($fullPath);
                $className = $namespace ? $namespace . '\\' . $controllerName : $controllerName;
                
                self::$controllerCache[$controllerName] = [
                    'class' => $className,
                    'file' => $fullPath
                ];
            }

            if (is_dir($fullPath)) {
                self::scanDirectory($fullPath, $projectRoot);
            }
        }
    }

    private static function extractNamespace(string $filePath): ?string
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $content = file_get_contents($filePath);
        
        if (preg_match('/^\s*namespace\s+([^;{\s]+)/m', $content, $matches)) {
            $namespace = trim($matches[1]);
            $namespace = rtrim($namespace, '\\');
            return $namespace;
        }

        return null;
    }

    private static function getProjectRoot(): string
    {
        // Router lives in: Core/routing/Router.php
        // Go up 2 levels to reach the project root
        $routerDir = __DIR__; // Core/routing
        $coreDir = dirname($routerDir); // Core
        $projectRoot = dirname($coreDir); // project root
        
        return realpath($projectRoot);
    }

    private static function normalizeUri($uri)
    {
        if (str_starts_with($uri, self::$basePath)) {
            $uri = substr($uri, strlen(self::$basePath));
        }

        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if ($path === '' || $path === null) $path = '/';
        if (!str_starts_with($path, '/')) $path = '/' . $path;

        return rtrim($path, '/') ?: '/';
    }

    private static function show404(string $uri, string $message = null)
    {
        http_response_code(404);

        echo "<!DOCTYPE html>
<html lang='ar'>
<head>
    <meta charset='UTF-8'>
    <title>404 - Page Not Found</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            font-size: 72px;
            margin: 0;
            color: #667eea;
            font-weight: bold;
        }
        p {
            font-size: 20px;
            color: #555;
            margin: 20px 0;
        }
        .details {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
            text-align: left;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
        }
        a:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>404</h1>
        <p>Page Not Found</p>
        <div class='details'>
            <strong>URI:</strong> $uri
        </div>
        <a href='/mes_projet/portfolio_mvc/public/'>Go Home</a>
    </div>
</body>
</html>";

        $logFile = __DIR__ . '/../logs/errors.log';
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        $logMessage = "[" . date('Y-m-d H:i:s') . "] 404 Error - Route not found: $uri";
        if ($message) {
            $logMessage .= " | Details: $message";
        }

        file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
    }

    private static function logDebug(string $message): void
    {
        $logFile = __DIR__ . '/../logs/router_debug.log';
        
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        $logMessage = "[" . date('Y-m-d H:i:s') . "] $message";
        file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
    }

    public static function clearCache(): void
    {
        self::$controllerCache = [];
        self::$cacheBuilt = false;
    }

    public static function getDiscoveredControllers(): array
    {
        if (!self::$cacheBuilt) {
            self::buildControllerCache();
        }
        
        return self::$controllerCache;
    }
}