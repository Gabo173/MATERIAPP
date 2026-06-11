<?php
/**
 * Modelo HistorialMovimiento
 * Acceso a datos de la tabla historial_movimientos
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'entities/HistorialMovimiento.php';

class HistorialMovimientoModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(HistorialMovimiento $historial) {
        $sql = "INSERT INTO historial_movimientos (id_solicitud, id_usuario, accion, estado_anterior, estado_nuevo) 
                VALUES (:id_solicitud, :id_usuario, :accion, :estado_anterior, :estado_nuevo)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_solicitud', $historial->getIdSolicitud());
        $stmt->bindValue(':id_usuario', $historial->getIdUsuario());
        $stmt->bindValue(':accion', $historial->getAccion());
        $stmt->bindValue(':estado_anterior', $historial->getEstadoAnterior());
        $stmt->bindValue(':estado_nuevo', $historial->getEstadoNuevo());
        
        return $stmt->execute();
    }

public function getBySolicitudId($idSolicitud) {
        $sql = "SELECT h.*, u.nombres_completos as nombre_usuario, s.observaciones_almacen
                FROM historial_movimientos h
                INNER JOIN usuarios u ON h.id_usuario = u.id
                INNER JOIN solicitudes s ON h.id_solicitud = s.id_solicitud
                WHERE h.id_solicitud = :id
                ORDER BY h.fecha_accion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $idSolicitud);
        $stmt->execute();
        
        $historial = [];
        while ($data = $stmt->fetch()) {
            $historial[] = $data;
        }
        
        return $historial;
    }

    public function getAll() {
        $sql = "SELECT h.*, u.nombres_completos as nombre_usuario, s.id_solicitud
                FROM historial_movimientos h
                INNER JOIN usuarios u ON h.id_usuario = u.id
                INNER JOIN solicitudes s ON h.id_solicitud = s.id_solicitud
                ORDER BY h.fecha_accion DESC
                LIMIT 100";
        
        $stmt = $this->db->query($sql);
        
        $historial = [];
        while ($data = $stmt->fetch()) {
            $historial[] = $data;
        }
        
        return $historial;
    }
}
