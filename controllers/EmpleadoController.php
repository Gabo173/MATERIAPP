<?php
/**
 * Controlador de Empleado
 * Gestiona solicitudes de materiales del empleado
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'models/SolicitudModel.php';
require_once ROOT_PATH . 'models/InventarioModel.php';
require_once ROOT_PATH . 'models/HistorialMovimientoModel.php';
require_once ROOT_PATH . 'models/UsuarioModel.php';
require_once ROOT_PATH . 'core/Validator.php';

class EmpleadoController {
    private $solicitudModel;
    private $inventarioModel;
    private $historialModel;
    private $usuarioModel;

    public function __construct() {
        Session::requireRole(ROLE_EMPLEADO);
        
        $this->solicitudModel = new SolicitudModel();
        $this->inventarioModel = new InventarioModel();
        $this->historialModel = new HistorialMovimientoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function solicitudes() {
        $usuarioId = Session::getUserId();
        $solicitudes = $this->solicitudModel->getByUsuarioId($usuarioId);
        
        View::render('empleado/solicitudes', [
            'solicitudes' => $solicitudes,
            'pageTitle' => 'Mis Solicitudes'
        ]);
    }

public function nueva() {
        $inventario = $this->inventarioModel->getAllConStock();
        
        View::render('empleado/nueva_solicitud', [
            'inventario' => $inventario,
            'pageTitle' => 'Nueva Solicitud'
        ]);
    }

public function crear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'empleado/solicitudes');
            exit;
        }

        $validator = new Validator($_POST);
        $validator->validateRequired('tipo_movimiento')
                  ->validateRequired('fecha_requerida')
                  ->validateRequired('justificacion');

        if ($validator->fails()) {
            Session::set('flash_error', implode('<br>', $validator->errors()));
            header('Location: ' . BASE_URL . 'empleado/nueva');
            exit;
        }

        $materialesJson = $_POST['materiales_json'] ?? '[]';
        $materiales = json_decode($materialesJson, true);
        
        if (empty($materiales)) {
            Session::set('flash_error', 'Debe seleccionar al menos un material');
            header('Location: ' . BASE_URL . 'empleado/nueva');
            exit;
        }

        try {
            $solicitud = new Solicitud([
                'id_usuario_solicitante' => Session::getUserId(),
                'tipo_movimiento' => $_POST['tipo_movimiento'],
                'estado' => STATUS_PENDIENTE,
                'fecha_requerida' => $_POST['fecha_requerida'],
                'observaciones' => $_POST['justificacion']
            ]);

            $detalles = [];
            foreach ($materiales as $material) {
                if (!empty($material['cantidad']) && $material['cantidad'] > 0) {
                    $detalles[] = [
                        'id_item' => $material['id_item'],
                        'cantidad_solicitada' => (int)$material['cantidad'],
                        'descripcion_uso' => ''
                    ];
                }
            }

            if (empty($detalles)) {
                Session::set('flash_error', 'Debe ingresar cantidades válidas para los materiales');
                header('Location: ' . BASE_URL . 'empleado/nueva');
                exit;
            }

            $this->solicitudModel->create($solicitud, $detalles);

            Session::set('flash_success', 'Solicitud creada exitosamente');
            header('Location: ' . BASE_URL . 'empleado/solicitudes');
            
        } catch (Exception $e) {
            Session::set('flash_error', 'Error al crear solicitud: ' . $e->getMessage());
            header('Location: ' . BASE_URL . 'empleado/nueva');
        }
        exit;
    }

    public function ver($id) {
        $resultado = $this->solicitudModel->findByIdWithDetails($id);
        
        if (!$resultado) {
            Session::set('flash_error', 'Solicitud no encontrada');
            header('Location: ' . BASE_URL . 'empleado/solicitudes');
            exit;
        }

        $solicitud = $resultado['solicitud'];
        
        if ($solicitud->getIdUsuarioSolicitante() != Session::getUserId()) {
            Session::set('flash_error', 'No tienes permiso para ver esta solicitud');
            header('Location: ' . BASE_URL . 'empleado/solicitudes');
            exit;
        }

        View::render('empleado/ver_solicitud', [
            'solicitud' => $solicitud,
            'nombre_solicitante' => $resultado['nombre_solicitante'],
            'detalles' => $resultado['detalles'],
            'historial' => $this->historialModel->getBySolicitudId($id),
            'pageTitle' => 'Solicitud #' . $id
        ]);
    }

public function perfil() {
        $usuario = $this->usuarioModel->findById(Session::getUserId());
        View::render('empleado/perfil', [
            'usuario' => $usuario,
            'pageTitle' => 'Mi Perfil'
        ]);
    }

    public function getStock() {
        if (!isset($_GET['id'])) {
            View::json(['error' => 'ID requerido'], 400);
        }

        $stock = $this->inventarioModel->getStockById($_GET['id']);
        View::json(['stock' => $stock]);
    }
}
