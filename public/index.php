<?php

use App\Core\Router;
use Dotenv\Dotenv;


//phpinfo();

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Autoload file not found at: " . $autoloadPath);
}
require_once $autoloadPath;

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Autoloader for dynamic loading of classes
spl_autoload_register(function ($class) {
    $classPath = str_replace(['App\\', '\\'], ['../src/', '/'], $class) . '.php';
    $fullPath = realpath($classPath);

    // Add debugging output
    /*echo "Attempting to load class: $class\n";
    echo "Generated path: $classPath\n";
    echo "Full resolved path: $fullPath\n";*/

    if ($fullPath && file_exists($fullPath)) {
        include $fullPath;
    } else {
        error_log("Class file not found: " . $classPath);
        http_response_code(500);
        die("Internal Server Error: Class not found for $class");
    }
});

// Ensure the Router file exists
$routerFilePath = realpath(__DIR__ . '/../src/core/Router.php');
if (!$routerFilePath || !file_exists($routerFilePath)) {
    error_log("Router file not found: " . $routerFilePath);
    http_response_code(500);
    die("Internal Server Error: Router not found");
}
require_once $routerFilePath;

// Initialize Router
$router = new Router();

// Add routes dynamically
$routesFile = __DIR__ . "/routes.yml"; // Ensure you have a routes.yml file
if (!file_exists($routesFile)) {
    error_log("Routes file not found: " . $routesFile);
    http_response_code(500);
    die("Internal Server Error: Routes configuration missing");
}

// Determine the HTTP method
$httpMethod = $_SERVER['REQUEST_METHOD'];

try {
    $routes = yaml_parse_file($routesFile);

    if (empty($routes) || !is_array($routes)) {
        throw new Exception("Invalid routes configuration");
    }

    // Loop through and register routes
    foreach ($routes as $uri => $route) {
        if (empty($route["controller"]) || empty($route["action"])) {
            throw new Exception("Invalid route definition for URI: " . $uri);
        }

        $controller = "\\App\\Controllers\\" . $route["controller"];
        $action = $route["action"];

        // Check if the controller class exists
        if (!class_exists($controller)) {
            throw new Exception("Controller class not found: " . $controller);
        }

        // Check if the method exists in the controller
        if (!method_exists($controller, $action)) {
            throw new Exception("Method not found: " . $action . " in " . $controller);
        }

        // Add routes based on HTTP method
        switch (strtoupper($httpMethod)) {
            case 'GET':
                $router->get($uri, $controller, $action);
                break;
            case 'POST':
                $router->post($uri, $controller, $action);
                break;
            // Add other HTTP methods as needed
            default:
                throw new Exception("Unsupported HTTP method: " . $httpMethod);
        }
    }

    // Start the Router
    $router->start();

} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die("Internal Server Error: " . $e->getMessage());
}