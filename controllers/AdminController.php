<?php
/**
 * Controlador de Administrador
 * Gestiona usuarios y supervisión del sistema
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'models/UsuarioModel.php';
require_once ROOT_PATH . 'models/SolicitudModel.php';
require_once ROOT_PATH . 'models/CedulaAdministradorModel.php';
require_once ROOT_PATH . 'models/HistorialMovimientoModel.php';
require_once ROOT_PATH . 'core/Validator.php';

class AdminController {
    private $usuarioModel;
    private $solicitudModel;
    private $cedulaModel;

    public function __construct() {
        Session::requireRole(ROLE_ADMIN);
        
        $this->usuarioModel = new UsuarioModel();
        $this->solicitudModel = new SolicitudModel();
        $this->cedulaModel = new CedulaAdministradorModel();
    }

    public function usuarios() {
        $usuarios = $this->usuarioModel->getAll();
        
        View::render('admin/gestion_cuentas', [
            'usuarios' => $usuarios,
            'pageTitle' => 'Gestión de Cuentas'
        ]);
    }

public function solicitudes() {
        $todas = $this->solicitudModel->getAll();
        
        View::render('admin/gestion_solicitudes', [
            'solicitudes' => $todas,
            'pageTitle' => 'Supervisión de Solicitudes'
        ]);
    }

    public function verSolicitud($id) {
        $solicitud = $this->solicitudModel->findById($id);
        
        if (!$solicitud) {
            Session::set('flash_error', 'Solicitud no encontrada');
            header('Location: ' . BASE_URL . 'admin/solicitudes');
            exit;
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->findById($solicitud->getIdUsuarioSolicitante());
        $nombre_solicitante = $usuario ? $usuario->getNombresCompletos() : 'Desconocido';

        $detalles = $this->solicitudModel->getDetalles($id);
        $historialModel = new HistorialMovimientoModel();
        $historial = $historialModel->getBySolicitudId($id);

        View::render('admin/ver_solicitud', [
            'solicitud' => $solicitud,
            'nombre_solicitante' => $nombre_solicitante,
            'detalles' => $detalles,
            'historial' => $historial,
            'pageTitle' => 'Detalle de Solicitud #' . $id
        ]);
    }

public function editarUsuario($id) {
        $usuario = $this->usuarioModel->findById($id);
        
        if (!$usuario) {
            Session::set('flash_error', 'Usuario no encontrado');
            header('Location: ' . BASE_URL . 'admin/usuarios');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST);
            $validator->validateRequired('nombres_completos')
                     ->validateEmail('correo_empresarial')
                     ->validateRequired('celular')
                     ->validateRequired('rol');

            if ($validator->fails()) {
                Session::set('flash_error', implode('<br>', $validator->errors()));
            } else {
                $data = $validator->getData();
                $usuario->setNombresCompletos($data['nombres_completos']);
                $usuario->setCorreoEmpresarial($data['correo_empresarial']);
                $usuario->setCelular($data['celular']);
                $usuario->setRol($data['rol']);

                $this->usuarioModel->update($usuario);

                $nuevaContrasena = $_POST['nueva_contrasena'] ?? '';
                if (!empty($nuevaContrasena)) {
                    if (strlen($nuevaContrasena) < 8) {
                        Session::set('flash_error', 'La contraseña debe tener al menos 8 caracteres');
                    } else {
                        $this->usuarioModel->updatePassword($usuario->getId(), $nuevaContrasena);
                        Session::set('flash_success', 'Usuario y contraseña actualizados exitosamente');
                        header('Location: ' . BASE_URL . 'admin/usuarios');
                        exit;
                    }
                } else {
                    Session::set('flash_success', 'Usuario actualizado exitosamente');
                }
                
                header('Location: ' . BASE_URL . 'admin/usuarios');
                exit;
            }
        }

        View::render('admin/editar_usuario', [
            'usuario' => $usuario,
            'pageTitle' => 'Editar Usuario'
        ]);
    }

    public function restablecerContrasena($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/usuarios');
            exit;
        }

        $nuevaContrasena = $_POST['nueva_contrasena'] ?? '';
        
        if (strlen($nuevaContrasena) < 8) {
            Session::set('flash_error', 'La contraseña debe tener al menos 8 caracteres');
        } else {
            $this->usuarioModel->updatePassword($id, $nuevaContrasena);
            Session::set('flash_success', 'Contraseña restablecida exitosamente');
        }

        header('Location: ' . BASE_URL . 'admin/usuarios');
        exit;
    }

    public function eliminarSolicitud($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/solicitudes');
            exit;
        }

        $this->solicitudModel->delete($id);
        Session::set('flash_success', 'Solicitud eliminada exitosamente');
        
        header('Location: ' . BASE_URL . 'admin/solicitudes');
        exit;
    }

public function estadisticas() {
        $totalUsuarios = count($this->usuarioModel->getAll());
        $totalSolicitudes = $this->solicitudModel->getCountAll();
        $pendientes = $this->solicitudModel->getCountByEstado(STATUS_PENDIENTE);
        $aprobadas = $this->solicitudModel->getCountByEstado(STATUS_APROBADO);
        $rechazadas = $this->solicitudModel->getCountByEstado(STATUS_RECHAZADO);

        View::render('admin/dashboard', [
            'totalUsuarios' => $totalUsuarios,
            'totalSolicitudes' => $totalSolicitudes,
            'pendientes' => $pendientes,
            'aprobadas' => $aprobadas,
            'rechazadas' => $rechazadas,
            'pageTitle' => 'Panel de Administración'
        ]);
    }

    public function registro() {
        $cedulas = $this->cedulaModel->getAll();
        
        View::render('admin/registro_cedulas', [
            'cedulas' => $cedulas,
            'pageTitle' => 'Registro de Cédulas Autorizadas'
        ]);
    }

    public function agregarCedula() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/registro');
            exit;
        }

        $cedula = $_POST['cedula'] ?? '';
        $nombreReferencia = $_POST['nombre_referencia'] ?? '';

        if (empty($cedula) || empty($nombreReferencia)) {
            Session::set('flash_error', 'Todos los campos son obligatorios');
        } elseif ($this->cedulaModel->exists($cedula)) {
            Session::set('flash_error', 'La cédula ya está registrada');
        } else {
            $nuevaCedula = new CedulaAdministrador();
            $nuevaCedula->setCedula($cedula);
            $nuevaCedula->setNombreReferencia($nombreReferencia);
            
            if ($this->cedulaModel->create($nuevaCedula)) {
                Session::set('flash_success', 'Cédula registrada exitosamente');
            } else {
                Session::set('flash_error', 'Error al registrar la cédula');
            }
        }

        header('Location: ' . BASE_URL . 'admin/registro');
        exit;
    }

    public function eliminarCedula($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/registro');
            exit;
        }

        if ($this->cedulaModel->delete($id)) {
            Session::set('flash_success', 'Cédula eliminada exitosamente');
        } else {
            Session::set('flash_error', 'Error al eliminar la cédula');
        }

        header('Location: ' . BASE_URL . 'admin/registro');
        exit;
    }
}
