<?php

namespace App\Core;
class Router
{
    private array $routes = [];

    public function get(string $path, string $controllerName, string $methodName): void
    {
        $this->routes[] = ["method" => "GET", "path" => $path, "controller" => $controllerName, "action" => $methodName];
    }

    public function post(string $path, string $controllerName, string $methodName): void
    {
        $this->routes[] = ["method" => "POST", "path" => $path, "controller" => $controllerName, "action" => $methodName];
    }

    public function start(): void
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $path = strtok($_SERVER["REQUEST_URI"], "?");

        foreach ($this->routes as $route) {
            if ($this->match($method, $path, $route)) {
                $controllerName = $route["controller"];
                $action = $route["action"];
                
                // Check if the controller and action exist
                if (!class_exists($controllerName) || !method_exists($controllerName, $action)) {
                    http_response_code(500);
                    echo "Controller or action not found.";
                    
                    return;
                }

                $controller = new $controllerName();
                $controller->$action();
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }

    private function match(string $method, string $path, array $route): bool
    {
        if ($method !== $route["method"]) {
            return false;
        }
        $routePath = preg_replace('/\{[^}]+}/', '([^/]+)', $route["path"]);
        $routePath = "#^" . $routePath . "$#";
        return preg_match($routePath, $path);
    }
}
