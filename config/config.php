<?php
/**
 * Configuración general del sistema
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

// Ruta base del proyecto
define('BASE_URL', '/materiapp/');

// Ruta absoluta del proyecto
define('ROOT_PATH', dirname(__DIR__) . '/');

// Nombre de la aplicación
define('APP_NAME', 'MateriApp');

// Tiempo de expiración de sesión en segundos (2 horas)
define('SESSION_EXPIRE', 7200);

// Roles disponibles
define('ROLE_EMPLEADO', 'Trabajador');
define('ROLE_ALMACEN', 'Almacen');
define('ROLE_ADMIN', 'Administrador');

// Estados de solicitud
define('STATUS_PENDIENTE', 'Pendiente');
define('STATUS_APROBADO', 'Aprobado');
define('STATUS_RECHAZADO', 'Rechazado');

// Tipos de movimiento
define('MOVIMIENTO_ENTRADA', 'Entrada');
define('MOVIMIENTO_SALIDA', 'Salida');
