<?php
/**
 * Entidad CedulaAdministrador
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class CedulaAdministrador {
    private $id;
    private $cedula;
    private $nombre_referencia;
    private $fecha_registro;

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

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCedula() {
        return $this->cedula;
    }

    public function setCedula($cedula) {
        $this->cedula = $cedula;
    }

    public function getNombreReferencia() {
        return $this->nombre_referencia;
    }

    public function setNombreReferencia($nombre_referencia) {
        $this->nombre_referencia = $nombre_referencia;
    }

    public function getFechaRegistro() {
        return $this->fecha_registro;
    }

    public function setFechaRegistro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'cedula' => $this->cedula,
            'nombre_referencia' => $this->nombre_referencia,
            'fecha_registro' => $this->fecha_registro
        ];
    }
}
