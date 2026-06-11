<?php
/**
 * Entidad Usuario
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class Usuario {
    private $id;
    private $cedula;
    private $nombres_completos;
    private $correo_empresarial;
    private $celular;
    private $rol;
    private $contrasena;
    private $fecha_registro;
    private $activo;

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

    public function getNombresCompletos() {
        return $this->nombres_completos;
    }

    public function setNombresCompletos($nombres_completos) {
        $this->nombres_completos = $nombres_completos;
    }

    public function getCorreoEmpresarial() {
        return $this->correo_empresarial;
    }

    public function setCorreoEmpresarial($correo_empresarial) {
        $this->correo_empresarial = $correo_empresarial;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function getFechaRegistro() {
        return $this->fecha_registro;
    }

    public function setFechaRegistro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'cedula' => $this->cedula,
            'nombres_completos' => $this->nombres_completos,
            'correo_empresarial' => $this->correo_empresarial,
            'celular' => $this->celular,
            'rol' => $this->rol,
            'contrasena' => $this->contrasena,
            'fecha_registro' => $this->fecha_registro,
            'activo' => $this->activo
        ];
    }
}
