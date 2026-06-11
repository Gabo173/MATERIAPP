<?php
/**
 * Punto de entrada principal
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

// Cargar configuración (define ROOT_PATH y BASE_URL)
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Cargar core del sistema
require_once ROOT_PATH . 'core/Database.php';
require_once ROOT_PATH . 'core/Session.php';
require_once ROOT_PATH . 'core/Router.php';
require_once ROOT_PATH . 'core/View.php';

// Iniciar sesión
Session::init();

// Crear router y definir rutas
$router = new Router();

// Rutas de autenticación
$router->get('auth/login', 'AuthController', 'login');
$router->post('auth/login', 'AuthController', 'login');
$router->get('auth/registro', 'AuthController', 'registro');
$router->post('auth/registro', 'AuthController', 'registro');
$router->get('auth/logout', 'AuthController', 'logout');
$router->get('auth/unauthorized', 'AuthController', 'unauthorized');

// Rutas de empleado
$router->get('empleado/solicitudes', 'EmpleadoController', 'solicitudes');
$router->get('empleado/nueva', 'EmpleadoController', 'nueva');
$router->post('empleado/crear', 'EmpleadoController', 'crear');
$router->get('empleado/ver/(\d+)', 'EmpleadoController', 'ver');
$router->get('empleado/perfil', 'EmpleadoController', 'perfil');

// Rutas de almacén
$router->get('almacen/solicitudes', 'AlmacenController', 'solicitudes');
$router->get('almacen/ver/(\d+)', 'AlmacenController', 'ver');
$router->post('almacen/aprobar/(\d+)', 'AlmacenController', 'aprobar');
$router->post('almacen/rechazar/(\d+)', 'AlmacenController', 'rechazar');
$router->get('almacen/inventario', 'AlmacenController', 'inventario');
$router->post('almacen/inventario/agregar', 'AlmacenController', 'agregarMaterial');
$router->get('almacen/material/(\d+)/editar', 'AlmacenController', 'editarMaterial');
$router->post('almacen/material/(\d+)/editar', 'AlmacenController', 'editarMaterial');
$router->post('almacen/material/(\d+)/eliminar', 'AlmacenController', 'eliminarMaterial');
$router->get('almacen/perfil', 'AlmacenController', 'perfil');

// Rutas de administrador
$router->get('admin/usuarios', 'AdminController', 'usuarios');
$router->get('admin/solicitudes', 'AdminController', 'solicitudes');
$router->get('admin/ver-solicitud/(\d+)', 'AdminController', 'verSolicitud');
$router->get('admin/registro', 'AdminController', 'registro');
$router->post('admin/agregar-cedula', 'AdminController', 'agregarCedula');
$router->post('admin/eliminar-cedula/(\d+)', 'AdminController', 'eliminarCedula');
$router->get('admin/usuario/(\d+)/editar', 'AdminController', 'editarUsuario');
$router->post('admin/usuario/(\d+)/editar', 'AdminController', 'editarUsuario');
$router->post('admin/usuario/(\d+)/restablecer', 'AdminController', 'restablecerContrasena');
$router->post('admin/solicitud/(\d+)/eliminar', 'AdminController', 'eliminarSolicitud');
$router->get('admin/dashboard', 'AdminController', 'estadisticas');

// Ruta por defecto
$router->get('', 'AuthController', 'login');

// Obtener URI y método
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Limpiar URI para el routing - quitar query string y BASE_URL
$uri = parse_url($uri, PHP_URL_PATH);

// Remover BASE_URL del inicio del URI
$basePath = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
if (!empty($basePath) && strpos($uri, '/' . $basePath) === 0) {
    $uri = substr($uri, strlen($basePath) + 1);
}

// Limpiar slash inicial
$uri = trim($uri, '/');

// Si la URI está vacía o es el root, redirigir directamente al login
if ($uri === '' || $uri === 'index.php') {
    header('Location: ' . BASE_URL . 'auth/login');
    exit;
}

// Dispatch de la ruta
try {
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    error_log("Error en MateriApp: " . $e->getMessage());
    error_log("URI: " . $uri);
    http_response_code(500);
    echo "Error interno del servidor: " . $e->getMessage();
}