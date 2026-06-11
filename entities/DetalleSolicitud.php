<?php
/**
 * Entidad DetalleSolicitud
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class DetalleSolicitud {
    private $id_detalle;
    private $id_solicitud;
    private $id_item;
    private $cantidad_solicitada;
    private $descripcion_uso;

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

    public function getIdDetalle() {
        return $this->id_detalle;
    }

    public function setIdDetalle($id_detalle) {
        $this->id_detalle = $id_detalle;
    }

    public function getIdSolicitud() {
        return $this->id_solicitud;
    }

    public function setIdSolicitud($id_solicitud) {
        $this->id_solicitud = $id_solicitud;
    }

    public function getIdItem() {
        return $this->id_item;
    }

    public function setIdItem($id_item) {
        $this->id_item = $id_item;
    }

    public function getCantidadSolicitada() {
        return $this->cantidad_solicitada;
    }

    public function setCantidadSolicitada($cantidad_solicitada) {
        $this->cantidad_solicitada = $cantidad_solicitada;
    }

    public function getDescripcionUso() {
        return $this->descripcion_uso;
    }

    public function setDescripcionUso($descripcion_uso) {
        $this->descripcion_uso = $descripcion_uso;
    }

    public function toArray() {
        return [
            'id_detalle' => $this->id_detalle,
            'id_solicitud' => $this->id_solicitud,
            'id_item' => $this->id_item,
            'cantidad_solicitada' => $this->cantidad_solicitada,
            'descripcion_uso' => $this->descripcion_uso
        ];
    }
}
