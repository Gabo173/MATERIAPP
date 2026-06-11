<?php
/**
 * Clase Router
 * Enrutamiento de URLs amigables
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class Router {
    private $routes = [];

    public function get($path, $controller, $action) {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function post($path, $controller, $action) {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

public function dispatch($uri, $method) {
        $uri = trim($uri, '/');
        
        foreach ($this->routes[$method] as $routePath => $route) {
            $pattern = '#^' . $routePath . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                
                $controllerPath = ROOT_PATH . 'controllers/' . $controllerName . '.php';
                
                if (file_exists($controllerPath)) {
                    require_once $controllerPath;
                    $controller = new $controllerName();
                    
                    if (method_exists($controller, $actionName)) {
                        array_shift($matches);
                        call_user_func_array([$controller, $actionName], $matches);
                        return;
                    }
                }
            }
        }
        
        http_response_code(404);
        require_once ROOT_PATH . 'views/errors/404.php';
    }

    public function redirect($path) {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
}
