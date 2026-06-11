# MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales

**Telemática SAS**

Sistema web para la gestión de solicitudes de materiales, control de inventario y administración de usuarios en entornos empresariales.

---

## 📋 Descripción

MateriApp es una aplicación web desarrollada en PHP que permite gestionar el flujo de solicitudes de materiales entre empleados, almacén y administradores. El sistema controla el inventario, valida accesos por roles y mantiene un historial de movimientos para auditoría.

---

## ✨ Características Principales

### 👥 Perfiles de Usuario

#### **Empleado / Trabajador**
- Crear solicitudes de materiales (entrada/salida)
- Consultar estado de solicitudes
- Ver detalle de solicitudes con historial
- Consultar inventario disponible

#### **Almacén**
- Recibir solicitudes de empleados
- Aprobar o rechazar solicitudes con observaciones
- **Gestión completa de inventario** (agregar, editar, eliminar materiales) ⭐
- Control automático de stock (entradas y salidas)
- Ver detalle de solicitudes con historial y observaciones
- Visualizar motivos de aprobación/rechazo en el historial

#### **Administrador**
- Gestión de cuentas de usuario
- Registro de cédulas autorizadas para crear administradores
- Supervisión de todas las solicitudes con detalles de aprobación/rechazo
- Dashboard con estadísticas del sistema
- Edición de usuarios y restablecimiento de contraseñas
- Visualización de motivos de aprobación y rechazo de solicitudes

---

## 🛠️ Requisitos Técnicos

### Software Necesario
- **PHP** 7.4 o superior
- **MySQL** 8.0 o superior
- **Apache** (incluido en XAMPP/WAMP)
- **Navegador web** (Chrome, Firefox, Edge)

### Servidor Local Recomendado
- **WAMP Server** (Windows)
- **XAMPP** (Multiplataforma)

---

## 📦 Instalación

### Paso 1: Clonar o Copiar el Proyecto

```bash
# Copiar la carpeta del proyecto al directorio www del servidor
C:\wamp64\www\Materiapp
```

### Paso 2: Configurar Base de Datos

1. Abrir phpMyAdmin (http://localhost/phpmyadmin)
2. Ejecutar el script SQL ubicado en:
   ```
   MateriApp/database/materiapp.sql
   ```
3. Verificar que se hayan creado:
   - 6 tablas
   - Datos de ejemplo (3 usuarios, 5 productos)

### Paso 3: Configurar Conexión a BD

Editar el archivo `config/database.php` si es necesario:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'materiapp');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```

### Paso 4: Acceder al Sistema

Abrir navegador e ingresar a:
```
http://localhost/materiapp/
```

---

## 🔑 Credenciales de Acceso

### Usuarios de Prueba

| Rol | Cédula | Correo | Contraseña |
|-----|--------|--------|------------|
| **Administrador** | 1234567890 | admin@telematicasas.com | admin123 |
| **Almacén** | 1122334455 | almacen@telematicasas.com | almacen123 |
| **Trabajador** | 5566778899 | empleado@telematicasas.com | empleado123 |

> **Nota:** Para crear un nuevo administrador, la cédula debe estar previamente autorizada en el módulo "Registro" del panel de administración.

---

## 📁 Estructura del Proyecto

```
MateriApp/
├── config/
│   ├── config.php          # Configuración general (rutas, constantes)
│   └── database.php        # Conexión a base de datos
├── controllers/
│   ├── AuthController.php      # Autenticación y registro
│   ├── AdminController.php     # Gestión de administradores
│   ├── EmpleadoController.php  # Módulo de empleados
│   └── AlmacenController.php   # Módulo de almacén
├── models/
│   ├── UsuarioModel.php            # Acceso a datos de usuarios
│   ├── SolicitudModel.php          # Gestión de solicitudes
│   ├── InventarioModel.php         # Control de inventario
│   ├── CedulaAdministradorModel.php # Validación de cédulas
│   └── HistorialMovimientoModel.php # Auditoría
├── entities/
│   ├── Usuario.php
│   ├── Solicitud.php
│   ├── Inventario.php
│   ├── DetalleSolicitud.php
│   ├── HistorialMovimiento.php
│   └── CedulaAdministrador.php
├── views/
│   ├── auth/           # Login y registro
│   ├── admin/          # Vistas de administrador
│   ├── empleado/       # Vistas de empleado
│   ├── almacen/        # Vistas de almacén
│   └── errors/         # Páginas de error
├── core/
│   ├── Database.php    # Conexión PDO
│   ├── Router.php      # Enrutamiento
│   ├── Session.php     # Manejo de sesiones
│   ├── View.php        # Renderizado de vistas
│   └── Validator.php   # Validación de datos
├── database/
│   └── materiapp.sql   # Script de base de datos
├── public/
│   ├── css/
│   │   └── styles.css  # Estilos del sistema
│   └── js/             # JavaScript (si aplica)
├── index.php           # Punto de entrada principal
└── README.md           # Este archivo
```

---

## 🔄 Flujo de Trabajo

### 1. Registro de Usuario
1. Usuario ingresa a "Crear cuenta nueva"
2. Completa formulario con datos personales
3. Selecciona rol (Administrador requiere cédula autorizada)
4. Sistema valida y crea cuenta
5. Redirección a login

### 2. Creación de Solicitudes (Empleado)
1. Empleado inicia sesión
2. Navega a "Nueva Solicitud"
3. Selecciona tipo de movimiento (Entrada/Salida)
4. Agrega productos del inventario
5. Describe uso de materiales
6. Envía solicitud

### 3. Gestión de Solicitudes (Almacén)
1. Almacenista inicia sesión
2. Revisa solicitudes pendientes
3. Verifica disponibilidad de productos
4. Aprueba o rechaza con observaciones
5. Sistema actualiza inventario automáticamente (suma para entradas, resta para salidas)

### 4. Gestión de Inventario (Almacén) ⭐
1. Almacenista accede al módulo "Inventario"
2. Puede agregar nuevos materiales con formulario simplificado
3. La bodega se asigna automáticamente
4. Puede editar cantidades y nombres de materiales existentes
5. Puede eliminar materiales obsoletos
6. Control visual del stock (colores según cantidad)
7. El enlace a Inventario está disponible desde todas las vistas del almacén

### 5. Visualización de Observaciones ⭐
1. Las solicitudes aprobadas/rechazadas muestran el motivo en "Información General"
2. El historial incluye una columna "Observaciones" con el comentario completo
3. Las observaciones se guardan en la base de datos y son persistentes

### 6. Administración del Sistema
1. Administrador gestiona cuentas de usuario
2. Registra cédulas autorizadas para nuevos admins
3. Supervisa todas las solicitudes con sus observaciones
4. Puede ver motivos de aprobación y rechazo
5. Consulta el detalle completo de cada solicitud
6. Elimina solicitudes si es necesario

---

## 🔒 Seguridad

### Validaciones Implementadas
- ✅ Contraseñas mínimas de 8 caracteres
- ✅ Cédulas únicas en el sistema
- ✅ Correos empresariales únicos
- ✅ Validación de cédulas autorizadas para administradores
- ✅ Sesiones con timeout
- ✅ Protección contra acceso no autorizado por roles

### Roles y Permisos
- **ROLE_EMPLEADO** → Acceso limitado a sus propias solicitudes
- **ROLE_ALMACEN** → Gestión de solicitudes e inventario
- **ROLE_ADMIN** → Control total del sistema

---

## 📊 Módulos del Sistema

### Módulo de Autenticación (`/auth`)
- Login
- Registro de usuarios
- Logout
- Página de no autorizado

### Módulo de Empleado (`/empleado`)
- Listar solicitudes
- Crear nueva solicitud
- Ver detalle de solicitud (con historial de la solicitud)
- Perfil de usuario

### Módulo de Almacén (`/almacen`)
- Solicitudes pendientes
- Ver y aprobar/rechazar (con registro de acciones)
- **Gestión de Inventario** ⭐
  - Listar todos los materiales
  - Agregar nuevos materiales (formulario simplificado)
  - Editar materiales existentes
  - Eliminar materiales
  - Bodega automática preconfigurada
  - Enlace accesible desde todas las vistas
- Ver observaciones de aprobación/rechazo en el historial ⭐
- Perfil de usuario

### Módulo de Administrador (`/admin`)
- Gestión de cuentas
- Registro de cédulas autorizadas ⭐
- Supervisión de solicitudes con observaciones
- **Visualización de motivos de aprobación/rechazo** ⭐
- Dashboard estadístico
- Edición de usuarios
- Restablecimiento de contraseñas
- Eliminación de solicitudes
- Historial completo con columnas de observaciones

---

## 🗄️ Base de Datos

### Tablas Principales

1. **usuarios** - Información de todos los usuarios
2. **cedulas_administrador** - Cédulas autorizadas para rol admin
3. **inventario** - Materiales disponibles
4. **solicitudes** - Cabecera de solicitudes
5. **detalle_solicitud** - Ítems por solicitud
6. **historial_movimientos** - Auditoría de acciones

### Relaciones
- Un usuario puede tener múltiples solicitudes
- Una solicitud contiene múltiples ítems del inventario
- Cada movimiento queda registrado en el historial

---

## 🎨 Tecnologías Utilizadas

### Backend
- PHP 7.4+ (Programación Orientada a Objetos)
- MySQL 8.0 (Base de datos relacional)
- PDO (Acceso seguro a base de datos)
- MVC (Patrón de diseño)

### Frontend
- HTML5 semántico
- CSS3 (Diseño responsivo)
- JavaScript (Validaciones)
- SVG (Iconos)

### Arquitectura
- **MVC** (Modelo-Vista-Controlador)
- **Router personalizado** (Enrutamiento amigable)
- **Session Handler** (Manejo de sesiones)
- **Validator** (Validación de formularios)

---

## 🚀 Funcionalidades Destacadas

1. ✅ **Registro de cédulas autorizadas** - Solo cédulas registradas pueden crear administradores
2. ✅ **Gestión integral de usuarios** - CRUD completo desde el panel admin
3. ✅ **Cambio de contraseña** - Desde el formulario de edición
4. ✅ **Validación en tiempo real** - Formularios con validación del lado del servidor
5. ✅ **Mensajes flash** - Notificaciones de éxito/error
6. ✅ **Control de inventario** - Actualización automática de stock (entradas/salidas)
7. ✅ **Gestión de inventario** - Módulo completo para almacén (agregar/editar/eliminar) ⭐
8. ✅ **Bodega automática** - Asignación automática de bodega preconfigurada
9. ✅ **Formularios simplificados** - Solo campos esenciales para agilidad
10. ✅ **Indicadores visuales de stock** - Colores según nivel de inventario
11. ✅ **Motivos de aprobación/rechazo visibles** - En información general e historial ⭐
12. ✅ **Observaciones persistentes** - Guardadas en base de datos y mostradas en todas las vistas
13. ✅ **Enlace de Inventario accesible** - Disponible desde todo el módulo de almacén
14. ✅ **Diseño responsivo** - Adaptable a diferentes dispositivos

---

## 📝 Notas Importantes

### Para el Profesor
- El sistema cuenta con **3 roles completamente funcionales**
- La validación de cédulas para administradores está implementada
- El módulo de registro de cédulas permite gestión completa (agregar/eliminar)
- Todas las rutas están correctamente configuradas
- La base de datos incluye datos de ejemplo para pruebas inmediatas

### Posibles Mejoras Futuras
- Encriptación de contraseñas (password_hash)
- Envío de correos electrónicos
- Exportación de reportes a PDF/Excel
- Recuperación de contraseña por email
- Subida de archivos adjuntos en solicitudes

---

## 👨‍💻 Autor

**Sistema desarrollado para la materia de Desarrollo Web**  
**Telemática SAS**  
**Fecha: 2026**

---

## 📞 Soporte

Para problemas técnicos o preguntas sobre el sistema, contactar al administrador del sistema.

---

## ✅ Checklist para Presentación

- [x] Base de datos creada con datos de ejemplo
- [x] Todos los módulos funcionales (Empleado, Almacén, Admin)
- [x] Registro de cédulas autorizadas implementado
- [x] Login y registro de usuarios operativo
- [x] Gestión de solicitudes completa
- [x] Control de inventario funcional
- [x] Diseño responsivo y profesional
- [x] Validaciones de seguridad implementadas
- [x] Documentación completa (README)

---

**¡Gracias por usar MateriApp!** 🎯