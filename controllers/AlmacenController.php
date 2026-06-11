<?php
/**
 * Controlador de Almacén
 * Gestiona aprobación y rechazo de solicitudes
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'models/SolicitudModel.php';
require_once ROOT_PATH . 'models/InventarioModel.php';
require_once ROOT_PATH . 'models/HistorialMovimientoModel.php';
require_once ROOT_PATH . 'models/UsuarioModel.php';
require_once ROOT_PATH . 'core/Validator.php';

class AlmacenController {
    private $solicitudModel;
    private $inventarioModel;
    private $historialModel;
    private $db;

    public function __construct() {
        Session::requireRole(ROLE_ALMACEN);
        
        $this->db = Database::getInstance();
        $this->solicitudModel = new SolicitudModel();
        $this->inventarioModel = new InventarioModel();
        $this->historialModel = new HistorialMovimientoModel();
    }

    public function solicitudes() {
        $pendientes = $this->solicitudModel->getByEstado(STATUS_PENDIENTE);
        
        View::render('almacen/solicitudes', [
            'pendientes' => $pendientes,
            'aprobadas' => $this->solicitudModel->getByEstado(STATUS_APROBADO),
            'rechazadas' => $this->solicitudModel->getByEstado(STATUS_RECHAZADO),
            'pageTitle' => 'Gestión de Solicitudes'
        ]);
    }

public function ver($id) {
        $resultado = $this->solicitudModel->findByIdWithDetails($id);
        
        if (!$resultado) {
            Session::set('flash_error', 'Solicitud no encontrada');
            header('Location: ' . BASE_URL . 'almacen/solicitudes');
            exit;
        }

        View::render('almacen/ver_solicitud', [
            'solicitud' => $resultado['solicitud'],
            'nombre_solicitante' => $resultado['nombre_solicitante'],
            'detalles' => $resultado['detalles'],
            'historial' => $this->historialModel->getBySolicitudId($id),
            'pageTitle' => 'Solicitud #' . $id
        ]);
    }

    public function aprobar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'almacen/solicitudes');
            exit;
        }

        try {
            $this->db->beginTransaction();

            $solicitud = $this->solicitudModel->findById($id);
            
            if (!$solicitud || $solicitud->getEstado() !== STATUS_PENDIENTE) {
                Session::set('flash_error', 'Solicitud no válida para aprobación');
                header('Location: ' . BASE_URL . 'almacen/solicitudes');
                exit;
            }

            $detalles = $this->solicitudModel->getDetalles($id);

foreach ($detalles as $detalle) {
                if (!$this->inventarioModel->checkStock($detalle['id_item'], $detalle['cantidad_solicitada'])) {
                    Session::set('flash_error', 'Stock insuficiente para el material: ' . $detalle['nombre_producto']);
                    header('Location: ' . BASE_URL . 'almacen/ver/' . $id);
                    exit;
                }
            }

            foreach ($detalles as $detalle) {
                if ($solicitud->getTipoMovimiento() === 'Salida') {
                    $this->inventarioModel->updateStock($detalle['id_item'], $detalle['cantidad_solicitada'], 'restar');
                } else {
                    $this->inventarioModel->updateStock($detalle['id_item'], $detalle['cantidad_solicitada'], 'sumar');
                }
            }

            $observaciones = $_POST['observaciones_almacen'] ?? '';

            $this->solicitudModel->updateEstado(
                $id,
                STATUS_APROBADO,
                Session::getUserId(),
                $observaciones
            );

            $this->historialModel->create(new HistorialMovimiento([
                'id_solicitud' => $id,
                'id_usuario' => Session::getUserId(),
                'accion' => 'Aprobación',
                'estado_anterior' => STATUS_PENDIENTE,
                'estado_nuevo' => STATUS_APROBADO
            ]));

            $this->db->commit();

            Session::set('flash_success', 'Solicitud aprobada exitosamente');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            Session::set('flash_error', 'Error al aprobar: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'almacen/solicitudes');
        exit;
    }

    public function rechazar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'almacen/solicitudes');
            exit;
        }

        try {
            $observaciones = $_POST['observaciones_almacen'] ?? '';

            $this->solicitudModel->updateEstado(
                $id,
                STATUS_RECHAZADO,
                Session::getUserId(),
                $observaciones
            );

            $this->historialModel->create(new HistorialMovimiento([
                'id_solicitud' => $id,
                'id_usuario' => Session::getUserId(),
                'accion' => 'Rechazo',
                'estado_anterior' => STATUS_PENDIENTE,
                'estado_nuevo' => STATUS_RECHAZADO
            ]));

            Session::set('flash_success', 'Solicitud rechazada');
            
        } catch (Exception $e) {
            Session::set('flash_error', 'Error al rechazar: ' . $e->getMessage());
        }

header('Location: ' . BASE_URL . 'almacen/solicitudes');
        exit;
    }

public function perfil() {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->findById(Session::getUserId());
        View::render('almacen/perfil', [
            'usuario' => $usuario,
            'pageTitle' => 'Mi Perfil'
        ]);
    }

    public function inventario() {
        $inventario = $this->inventarioModel->getAll();
        
        View::render('almacen/inventario', [
            'inventario' => $inventario,
            'pageTitle' => 'Gestión de Inventario'
        ]);
    }

    public function agregarMaterial() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'almacen/inventario');
            exit;
        }

        $validator = new Validator($_POST);
        $validator->validateRequired('nombre_producto')
                 ->validateRequired('cod_producto')
                 ->validateRequired('cantidad_disponible')
                 ->validateRequired('nombre_bodega')
                 ->validateRequired('cod_bodega');

        if ($validator->fails()) {
            Session::set('flash_error', implode('<br>', $validator->errors()));
            header('Location: ' . BASE_URL . 'almacen/inventario');
            exit;
        }

        $data = $validator->getData();
        $item = new Inventario($data);
        
        if ($this->inventarioModel->create($item)) {
            Session::set('flash_success', 'Material agregado exitosamente');
        } else {
            Session::set('flash_error', 'Error al agregar el material');
        }

        header('Location: ' . BASE_URL . 'almacen/inventario');
        exit;
    }

    public function editarMaterial($id) {
        $item = $this->inventarioModel->findById($id);
        
        if (!$item) {
            Session::set('flash_error', 'Material no encontrado');
            header('Location: ' . BASE_URL . 'almacen/inventario');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST);
            $validator->validateRequired('nombre_producto')
                     ->validateRequired('cod_producto')
                     ->validateRequired('cantidad_disponible')
                     ->validateRequired('nombre_bodega')
                     ->validateRequired('cod_bodega');

            if ($validator->fails()) {
                Session::set('flash_error', implode('<br>', $validator->errors()));
            } else {
                $data = $validator->getData();
                $item->fill($data);
                
                if ($this->inventarioModel->update($item)) {
                    Session::set('flash_success', 'Material actualizado exitosamente');
                    header('Location: ' . BASE_URL . 'almacen/inventario');
                    exit;
                } else {
                    Session::set('flash_error', 'Error al actualizar el material');
                }
            }
        }

        View::render('almacen/editar_material', [
            'item' => $item,
            'pageTitle' => 'Editar Material'
        ]);
    }

    public function eliminarMaterial($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'almacen/inventario');
            exit;
        }

        if ($this->inventarioModel->delete($id)) {
            Session::set('flash_success', 'Material eliminado exitosamente');
        } else {
            Session::set('flash_error', 'Error al eliminar el material');
        }

        header('Location: ' . BASE_URL . 'almacen/inventario');
        exit;
    }
}
