-- =====================================================
-- MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
-- Base de datos MySQL 8.0
-- =====================================================
-- Para WAMP Server (localhost)

-- =====================================================
-- Tabla: CEDULAS ADMINISTRADOR
-- Valida el acceso al rol administrador
-- =====================================================
CREATE TABLE cedulas_administrador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    nombre_referencia VARCHAR(100) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: USUARIOS
-- Almacena la información de todos los usuarios del sistema
-- =====================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    nombres_completos VARCHAR(150) NOT NULL,
    correo_empresarial VARCHAR(100) NOT NULL UNIQUE,
    celular VARCHAR(20) NOT NULL,
    rol ENUM('Trabajador', 'Almacen', 'Administrador') NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: INVENTARIO
-- Almacena los materiales disponibles en el almacén
-- =====================================================
CREATE TABLE inventario (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    serial VARCHAR(50),
    mac VARCHAR(50),
    nombre_producto VARCHAR(150) NOT NULL,
    nombre_marca VARCHAR(100),
    referencia VARCHAR(100),
    cod_producto VARCHAR(50) NOT NULL,
    cantidad_disponible INT NOT NULL DEFAULT 0,
    nombre_bodega VARCHAR(100) NOT NULL,
    cod_bodega VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: SOLICITUDES
-- Registra las solicitudes de materiales
-- =====================================================
CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_solicitante INT NOT NULL,
    tipo_movimiento ENUM('Entrada', 'Salida') NOT NULL,
    estado ENUM('Pendiente', 'Aprobado', 'Rechazado') NOT NULL DEFAULT 'Pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta TIMESTAMP NULL,
    id_usuario_almacen INT NULL,
    observaciones TEXT,
    observaciones_almacen TEXT,
    FOREIGN KEY (id_usuario_solicitante) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_almacen) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: DETALLE SOLICITUD
-- Detalle de ítems por cada solicitud
-- =====================================================
CREATE TABLE detalle_solicitud (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT NOT NULL,
    id_item INT NOT NULL,
    cantidad_solicitada INT NOT NULL,
    descripcion_uso TEXT,
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES inventario(id_item) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: HISTORIAL MOVIMIENTOS
-- Auditoría de todas las acciones del sistema
-- =====================================================
CREATE TABLE historial_movimientos (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT NOT NULL,
    id_usuario INT NOT NULL,
    accion VARCHAR(50) NOT NULL,
    estado_anterior VARCHAR(20),
    estado_nuevo VARCHAR(20),
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS DE EJEMPLO
-- =====================================================

-- Cédulas autorizadas para administrador
INSERT INTO cedulas_administrador (cedula, nombre_referencia) VALUES
('1234567890', 'Administrador Principal'),
('9876543210', 'Administrador Secundario');

-- Usuario administrador de ejemplo (contraseña: admin123)
INSERT INTO usuarios (cedula, nombres_completos, correo_empresarial, celular, rol, contrasena) VALUES
('1234567890', 'Admin Principal', 'admin@telematicasas.com', '3001234567', 'Administrador', 'admin123');

-- Usuario almacenista de ejemplo (contraseña: almacen123)
INSERT INTO usuarios (cedula, nombres_completos, correo_empresarial, celular, rol, contrasena) VALUES
('1122334455', 'Juan Almacenista', 'almacen@telematicasas.com', '3009876543', 'Almacen', 'almacen123');

-- Usuario empleado de ejemplo (contraseña: empleado123)
INSERT INTO usuarios (cedula, nombres_completos, correo_empresarial, celular, rol, contrasena) VALUES
('5566778899', 'MariaEmpleado', 'empleado@telematicasas.com', '3005551234', 'Trabajador', 'empleado123');

-- Inventario de ejemplo
INSERT INTO inventario (serial, mac, nombre_producto, nombre_marca, referencia, cod_producto, cantidad_disponible, nombre_bodega, cod_bodega) VALUES
('SER001', 'MAC001', 'Cable UTP Cat 6', 'Panduit', 'CAB-UTP-06', 'CAB-UTP-06', 150, 'Bodega Principal - Barrancabermeja', 'BP-BER-001'),
('SER002', 'MAC002', 'Patch Panel 24 Puertos', 'Panduit', 'PP-24P', 'PP-24P', 15, 'Bodega Principal - Barrancabermeja', 'BP-BER-001'),
('SER003', 'MAC003', 'Conector RJ45', '3M', 'CON-RJ45', 'CON-RJ45', 300, 'Bodega Principal - Barrancabermeja', 'BP-BER-001'),
('SER004', 'MAC004', 'Switch 24 Puertos', 'Cisco', 'SW-24P', 'SW-24P', 10, 'Bodega Principal - Barrancabermeja', 'BP-BER-001'),
('SER005', 'MAC005', 'Router Empresarial', 'Cisco', 'ROUT-ENT', 'ROUT-ENT', 5, 'Bodega Principal - Barrancabermeja', 'BP-BER-001');

-- =====================================================
-- ÍNDICES PARA MEJORAR RENDIMIENTO
-- =====================================================
CREATE INDEX idx_usuarios_correo ON usuarios(correo_empresarial);
CREATE INDEX idx_usuarios_rol ON usuarios(rol);
CREATE INDEX idx_solicitudes_estado ON solicitudes(estado);
CREATE INDEX idx_solicitudes_fecha ON solicitudes(fecha_solicitud);
CREATE INDEX idx_historial_fecha ON historial_movimientos(fecha_accion);