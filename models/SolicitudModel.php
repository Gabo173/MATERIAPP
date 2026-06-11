<?php
/**
 * Modelo Solicitud
 * Acceso a datos de la tabla solicitudes y detalle_solicitud
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'entities/Solicitud.php';
require_once ROOT_PATH . 'entities/DetalleSolicitud.php';

class SolicitudModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(Solicitud $solicitud, array $detalles) {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO solicitudes (id_usuario_solicitante, tipo_movimiento, estado, observaciones) 
                    VALUES (:id_usuario_solicitante, :tipo_movimiento, :estado, :observaciones)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_usuario_solicitante', $solicitud->getIdUsuarioSolicitante());
            $stmt->bindValue(':tipo_movimiento', $solicitud->getTipoMovimiento());
            $stmt->bindValue(':estado', $solicitud->getEstado());
            $stmt->bindValue(':observaciones', $solicitud->getObservaciones());
            
            $stmt->execute();
            $idSolicitud = $this->db->lastInsertId();
            
            foreach ($detalles as $detalle) {
                $sqlDetalle = "INSERT INTO detalle_solicitud (id_solicitud, id_item, cantidad_solicitada, descripcion_uso) 
                               VALUES (:id_solicitud, :id_item, :cantidad_solicitada, :descripcion_uso)";
                
                $stmtDetalle = $this->db->prepare($sqlDetalle);
                $stmtDetalle->bindValue(':id_solicitud', $idSolicitud);
                $stmtDetalle->bindValue(':id_item', $detalle['id_item']);
                $stmtDetalle->bindValue(':cantidad_solicitada', $detalle['cantidad_solicitada']);
                $stmtDetalle->bindValue(':descripcion_uso', $detalle['descripcion_uso']);
                $stmtDetalle->execute();
            }
            
            $this->db->commit();
            return $idSolicitud;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function findById($id) {
        $sql = "SELECT * FROM solicitudes WHERE id_solicitud = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? new Solicitud($data) : null;
    }

    public function findByIdWithDetails($id) {
        $sql = "SELECT s.*, u.nombres_completos as nombre_solicitante, u.correo_empresarial as correo_solicitante
                FROM solicitudes s
                INNER JOIN usuarios u ON s.id_usuario_solicitante = u.id
                WHERE s.id_solicitud = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $solicitudData = $stmt->fetch();
        
        if (!$solicitudData) {
            return null;
        }
        
        $solicitud = new Solicitud($solicitudData);
        
        $sqlDetalles = "SELECT ds.*, i.nombre_producto, i.cod_producto, i.cantidad_disponible, i.nombre_bodega
                        FROM detalle_solicitud ds
                        INNER JOIN inventario i ON ds.id_item = i.id_item
                        WHERE ds.id_solicitud = :id";
        
        $stmtDetalles = $this->db->prepare($sqlDetalles);
        $stmtDetalles->bindValue(':id', $id);
        $stmtDetalles->execute();
        
        $detalles = [];
        while ($detalleData = $stmtDetalles->fetch()) {
            $detalles[] = $detalleData;
        }
        
        return [
            'solicitud' => $solicitud,
            'nombre_solicitante' => $solicitudData['nombre_solicitante'],
            'correo_solicitante' => $solicitudData['correo_solicitante'],
            'detalles' => $detalles
        ];
    }

    public function getByUsuarioId($usuarioId) {
        $sql = "SELECT * FROM solicitudes WHERE id_usuario_solicitante = :id ORDER BY fecha_solicitud DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $usuarioId);
        $stmt->execute();
        
        $solicitudes = [];
        while ($data = $stmt->fetch()) {
            $solicitudes[] = new Solicitud($data);
        }
        
        return $solicitudes;
    }

    public function getByEstado($estado) {
        $sql = "SELECT s.*, u.nombres_completos as nombre_solicitante 
                FROM solicitudes s
                INNER JOIN usuarios u ON s.id_usuario_solicitante = u.id
                WHERE s.estado = :estado
                ORDER BY s.fecha_solicitud DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->execute();
        
        $solicitudes = [];
        while ($data = $stmt->fetch()) {
            $solicitudes[] = [
                'solicitud' => new Solicitud($data),
                'nombre_solicitante' => $data['nombre_solicitante']
            ];
        }
        
        return $solicitudes;
    }

    public function getAll() {
        $sql = "SELECT s.*, u.nombres_completos as nombre_solicitante, ua.nombres_completos as nombre_almacenista
                FROM solicitudes s
                INNER JOIN usuarios u ON s.id_usuario_solicitante = u.id
                LEFT JOIN usuarios ua ON s.id_usuario_almacen = ua.id
                ORDER BY s.fecha_solicitud DESC";
        
        $stmt = $this->db->query($sql);
        
        $solicitudes = [];
        while ($data = $stmt->fetch()) {
            $solicitudes[] = [
                'solicitud' => new Solicitud($data),
                'nombre_solicitante' => $data['nombre_solicitante'],
                'nombre_almacenista' => $data['nombre_almacenista']
            ];
        }
        
        return $solicitudes;
    }

public function updateEstado($idSolicitud, $estado, $idUsuarioAlmacen, $observaciones = null) {
        $sql = "UPDATE solicitudes SET 
                estado = :estado,
                id_usuario_almacen = :id_usuario_almacen,
                observaciones_almacen = :observaciones_almacen,
                fecha_respuesta = CURRENT_TIMESTAMP
                WHERE id_solicitud = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindValue(':id_usuario_almacen', $idUsuarioAlmacen, PDO::PARAM_INT);
        $stmt->bindValue(':observaciones_almacen', $observaciones ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':id', $idSolicitud, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM solicitudes WHERE id_solicitud = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    public function getDetalles($idSolicitud) {
        $sql = "SELECT ds.*, i.nombre_producto, i.cod_producto, i.cantidad_disponible, i.nombre_bodega
                FROM detalle_solicitud ds
                INNER JOIN inventario i ON ds.id_item = i.id_item
                WHERE ds.id_solicitud = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $idSolicitud);
        $stmt->execute();
        
        $detalles = [];
        while ($data = $stmt->fetch()) {
            $detalles[] = $data;
        }
        
        return $detalles;
    }

    public function getCountByEstado($estado) {
        $sql = "SELECT COUNT(*) FROM solicitudes WHERE estado = :estado";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    public function getCountAll() {
        $sql = "SELECT COUNT(*) FROM solicitudes";
        return $this->db->query($sql)->fetchColumn();
    }
}
