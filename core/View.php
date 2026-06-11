<?php
/**
 * Clase View
 * Manejo de plantillas y vistas
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class View {
    
    public static function render($view, $data = []) {
        extract($data);
        
        $viewPath = ROOT_PATH . 'views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("Vista no encontrada: {$view}");
        }
    }

    public static function renderPartial($view, $data = []) {
        extract($data);
        
        $viewPath = ROOT_PATH . 'views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function redirect($path) {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
}
