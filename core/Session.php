<?php
/**
 * Clase Session
 * Manejo de sesiones PHP con tiempo de expiración
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class Session {
    
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
        self::updateLastActivity();
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function destroy($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroyAll() {
        session_destroy();
        self::init();
    }

    public static function isLoggedIn() {
        return self::has('user_id') && self::has('user_role');
    }

    public static function getUserId() {
        return self::get('user_id');
    }

    public static function getUserRole() {
        return self::get('user_role');
    }

    public static function getUserName() {
        return self::get('user_name');
    }

    public static function getUserEmail() {
        return self::get('user_email');
    }

    private static function updateLastActivity() {
        $_SESSION['last_activity'] = time();
    }

    public static function checkExpiration() {
        if (isset($_SESSION['last_activity'])) {
            if ((time() - $_SESSION['last_activity']) > SESSION_EXPIRE) {
                self::destroyAll();
                return false;
            }
        }
        return true;
    }

    public static function requireLogin() {
        self::init();
        
        if (!self::isLoggedIn() || !self::checkExpiration()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    public static function requireRole($allowedRoles) {
        self::requireLogin();
        
        $userRole = self::getUserRole();
        
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        
        if (!in_array($userRole, $allowedRoles)) {
            header('Location: ' . BASE_URL . 'auth/unauthorized');
            exit;
        }
    }
}
