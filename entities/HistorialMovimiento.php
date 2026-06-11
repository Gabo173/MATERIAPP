<?php
/**
 * Entidad HistorialMovimiento
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class HistorialMovimiento {
    private $id_historial;
    private $id_solicitud;
    private $id_usuario;
    private $accion;
    private $estado_anterior;
    private $estado_nuevo;
    private $fecha_accion;

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
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    public function getIdHistorial() {
        return $this->id_historial;
    }

    public function setIdHistorial($id_historial) {
        $this->id_historial = $id_historial;
    }

    public function getIdSolicitud() {
        return $this->id_solicitud;
    }

    public function setIdSolicitud($id_solicitud) {
        $this->id_solicitud = $id_solicitud;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getAccion() {
        return $this->accion;
    }

    public function setAccion($accion) {
        $this->accion = $accion;
    }

    public function getEstadoAnterior() {
        return $this->estado_anterior;
    }

    public function setEstadoAnterior($estado_anterior) {
        $this->estado_anterior = $estado_anterior;
    }

    public function getEstadoNuevo() {
        return $this->estado_nuevo;
    }

    public function setEstadoNuevo($estado_nuevo) {
        $this->estado_nuevo = $estado_nuevo;
    }

    public function getFechaAccion() {
        return $this->fecha_accion;
    }

    public function setFechaAccion($fecha_accion) {
        $this->fecha_accion = $fecha_accion;
    }

    public function toArray() {
        return [
            'id_historial' => $this->id_historial,
            'id_solicitud' => $this->id_solicitud,
            'id_usuario' => $this->id_usuario,
            'accion' => $this->accion,
            'estado_anterior' => $this->estado_anterior,
            'estado_nuevo' => $this->estado_nuevo,
            'fecha_accion' => $this->fecha_accion
        ];
    }
}
