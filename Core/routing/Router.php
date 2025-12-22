<?php

class Router
{
    private static array $routes = [];
    private static ?string $basePath = null;
    private static bool $autoRouting = true;

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

    public static function dispatch()
    {
        self::$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = self::normalizeUri($_SERVER['REQUEST_URI']);

        // First: check manually registered routes
        if (isset(self::$routes[$method][$uri])) {
            $action = self::$routes[$method][$uri];
            return self::executeAction($action);
        }

        // Second: automatic routing
        if (self::$autoRouting) {
            return self::autoRoute($uri, $method);
        }

        // If no route is found
        self::show404($uri);
    }

    private static function autoRoute(string $uri, string $method)
    {
        // Remove the leading slash
        $uri = trim($uri, '/');
        
        // If URI is empty, use default controller
        if (empty($uri)) {
            $controllerName = 'UploadController';
            $methodName = 'index';
        } else {
            // Split URI into segments
            $segments = explode('/', $uri);
            
            // First part = Controller name
            $controllerName = ucfirst($segments[0]) . 'Controller';
            
            // Second part = Method name (default to 'index')
            $methodName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] : 'index';
            
            // Convert method name from kebab-case to camelCase
            // Example: upload-file => uploadFile
            $methodName = lcfirst(str_replace('-', '', ucwords($methodName, '-')));
        }

        // Find the Controller in all folders (app and services)
        $controllerInfo = self::findController($controllerName);

        if (!$controllerInfo) {
            self::show404($uri, "Controller not found: $controllerName in app or services folders");
            return;
        }

        // Load the Controller file
        $controllerFile = $controllerInfo['file'];
        $controllerClass = $controllerInfo['class'];
        
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

        // Check if the method exists
        if (!method_exists($controller, $methodName)) {
            self::show404($uri, "Method '$methodName' not found in $controllerClass");
            return;
        }

        // Execute the method
        return $controller->$methodName();
    }

    private static function executeAction($action)
    {
        if (is_callable($action)) {
            return $action();
        }

        if (is_string($action) && strpos($action, '@') !== false) {
            $parts = explode('@', $action);

            if (count($parts) === 2) {
                list($controller, $methodName) = $parts;
                $controllerInfo = self::findController($controller);

                if (!$controllerInfo) {
                    throw new Exception("Controller not found: $controller");
                }
                
                $controllerClass = $controllerInfo['class'];
                $controllerFile = $controllerInfo['file'];
            } elseif (count($parts) === 3) {
                list($folder, $controller, $methodName) = $parts;
                $controllerClass = "app\\$folder\\Controllers\\$controller";
                $controllerFile = self::resolveControllerFile($controllerClass);
            } else {
                throw new Exception("Invalid action format: $action");
            }

            if ($controllerFile && file_exists($controllerFile)) {
                require_once $controllerFile;
            } else {
                throw new Exception("Controller file not found: $controllerFile");
            }

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

    /**
     * البحث عن Controller في المجلدات (app و services)
     * يرجع array يحتوي على class و file أو null
     */
    private static function findController(string $controllerName): ?array
    {
        $projectRoot = realpath(__DIR__ . '/../..');
        $searchedPaths = []; // للتسجيل في حالة الخطأ
        
        // 1. البحث في مجلد app/
        $appPath = $projectRoot . '/app';
        if (is_dir($appPath)) {
            // البحث في المجلدات الفرعية داخل app/
            $folders = array_filter(glob($appPath . '/*'), 'is_dir');
            foreach ($folders as $folderPath) {
                $folderName = basename($folderPath);
                $controllerFile = $folderPath . '/Controllers/' . $controllerName . '.php';
                $searchedPaths[] = $controllerFile;

                if (file_exists($controllerFile)) {
                    return [
                        'class' => "app\\$folderName\\Controllers\\$controllerName",
                        'file' => $controllerFile
                    ];
                }
            }

            // البحث مباشرة في app/Controllers
            $directControllerFile = $appPath . '/Controllers/' . $controllerName . '.php';
            $searchedPaths[] = $directControllerFile;
            if (file_exists($directControllerFile)) {
                return [
                    'class' => "app\\Controllers\\$controllerName",
                    'file' => $directControllerFile
                ];
            }
        }

        // 2. البحث في مجلد services/
        $servicesPath = $projectRoot . '/services';
        if (is_dir($servicesPath)) {
            // البحث مباشرة في services/ (الملفات في الجذر)
            $directRootServiceFile = $servicesPath . '/' . $controllerName . '.php';
            $searchedPaths[] = $directRootServiceFile;
            if (file_exists($directRootServiceFile)) {
                return [
                    'class' => "services\\$controllerName",
                    'file' => $directRootServiceFile
                ];
            }

            // البحث في المجلدات الفرعية داخل services/
            $serviceFolders = array_filter(glob($servicesPath . '/*'), 'is_dir');
            foreach ($serviceFolders as $folderPath) {
                $folderName = basename($folderPath);
                $controllerFile = $folderPath . '/Controllers/' . $controllerName . '.php';
                $searchedPaths[] = $controllerFile;

                if (file_exists($controllerFile)) {
                    return [
                        'class' => "services\\$folderName\\Controllers\\$controllerName",
                        'file' => $controllerFile
                    ];
                }
            }

            // البحث مباشرة في services/Controllers
            $directServiceControllerFile = $servicesPath . '/Controllers/' . $controllerName . '.php';
            $searchedPaths[] = $directServiceControllerFile;
            if (file_exists($directServiceControllerFile)) {
                return [
                    'class' => "services\\Controllers\\$controllerName",
                    'file' => $directServiceControllerFile
                ];
            }
        }

        // تسجيل المسارات التي تم البحث فيها
        self::logSearchedPaths($controllerName, $searchedPaths);

        return null;
    }

    /**
     * تسجيل المسارات التي تم البحث فيها
     */
    private static function logSearchedPaths(string $controllerName, array $paths)
    {
        $logFile = __DIR__ . '/../logs/errors.log';
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        $logMessage = "[" . date('Y-m-d H:i:s') . "] Searching for $controllerName in:\n";
        foreach ($paths as $path) {
            $exists = file_exists($path) ? 'EXISTS' : 'NOT FOUND';
            $logMessage .= "  - [$exists] $path\n";
        }

        file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
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

    private static function resolveControllerFile(string $fullyQualifiedClass): ?string
    {
        $projectRoot = realpath(__DIR__ . '/../..');
        $relative = str_replace('\\', '/', $fullyQualifiedClass) . '.php';
        $full = $projectRoot . '/' . $relative;
        return $full;
    }

    private static function show404(string $uri, string $message = null)
    {
        // Send 404 HTTP code to the user
        http_response_code(404);

        // Simple display for the user
        echo "<!DOCTYPE html>
<html lang='ar'>
<head>
    <meta charset='UTF-8'>
    <title>Page Not Found</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
            color: #333;
        }
        .container {
            background-color: #fff;
            display: inline-block;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #e74c3c;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>404</h1>
        <p>Page not found.</p>
    </div>
</body>
</html>";

        // Automatically create log file inside the project folder
        $logFile = __DIR__ . '/../logs/errors.log';
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        // Prepare the error message with details
        $logMessage  = "[" . date('Y-m-d H:i:s') . "] 404 Error - Route not found: $uri";
        if ($message) {
            $logMessage .= " | Details: $message";
        }

        // Write the message to the log file
        file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
    }
}