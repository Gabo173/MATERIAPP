<?php
/**
 * Entidad Solicitud
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class Solicitud {
    private $id_solicitud;
    private $id_usuario_solicitante;
    private $tipo_movimiento;
    private $estado;
    private $fecha_solicitud;
    private $fecha_respuesta;
    private $id_usuario_almacen;
    private $observaciones;
    private $observaciones_almacen;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill($data) {
        foreach ($data as $key => $value) {
            $method = 'set' . $this->snakeToCamel($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

private function snakeToCamel($string) {
        return str_replace('_', '', ucwords($string, '_'));
    }

    public function getIdSolicitud() {
        return $this->id_solicitud;
    }

    public function setIdSolicitud($id_solicitud) {
        $this->id_solicitud = $id_solicitud;
    }

    public function getIdUsuarioSolicitante() {
        return $this->id_usuario_solicitante;
    }

    public function setIdUsuarioSolicitante($id_usuario_solicitante) {
        $this->id_usuario_solicitante = $id_usuario_solicitante;
    }

    public function getTipoMovimiento() {
        return $this->tipo_movimiento;
    }

    public function setTipoMovimiento($tipo_movimiento) {
        $this->tipo_movimiento = $tipo_movimiento;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getFechaSolicitud() {
        return $this->fecha_solicitud;
    }

    public function setFechaSolicitud($fecha_solicitud) {
        $this->fecha_solicitud = $fecha_solicitud;
    }

    public function getFechaRespuesta() {
        return $this->fecha_respuesta;
    }

    public function setFechaRespuesta($fecha_respuesta) {
        $this->fecha_respuesta = $fecha_respuesta;
    }

    public function getIdUsuarioAlmacen() {
        return $this->id_usuario_almacen;
    }

    public function setIdUsuarioAlmacen($id_usuario_almacen) {
        $this->id_usuario_almacen = $id_usuario_almacen;
    }

    public function getObservaciones() {
        return $this->observaciones;
    }

    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }

    public function getObservacionesAlmacen() {
        return $this->observaciones_almacen;
    }

    public function setObservacionesAlmacen($observaciones_almacen) {
        $this->observaciones_almacen = $observaciones_almacen;
    }

    public function toArray() {
        return [
            'id_solicitud' => $this->id_solicitud,
            'id_usuario_solicitante' => $this->id_usuario_solicitante,
            'tipo_movimiento' => $this->tipo_movimiento,
            'estado' => $this->estado,
            'fecha_solicitud' => $this->fecha_solicitud,
            'fecha_respuesta' => $this->fecha_respuesta,
            'id_usuario_almacen' => $this->id_usuario_almacen,
            'observaciones' => $this->observaciones,
            'observaciones_almacen' => $this->observaciones_almacen
        ];
    }
}
