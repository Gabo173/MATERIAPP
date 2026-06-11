<?php
/**
 * Entidad Inventario
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class Inventario {
    private $id_item;
    private $serial;
    private $mac;
    private $nombre_producto;
    private $nombre_marca;
    private $referencia;
    private $cod_producto;
    private $cantidad_disponible;
    private $nombre_bodega;
    private $cod_bodega;

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

    public function getIdItem() {
        return $this->id_item;
    }

    public function setIdItem($id_item) {
        $this->id_item = $id_item;
    }

    public function getSerial() {
        return $this->serial;
    }

    public function setSerial($serial) {
        $this->serial = $serial;
    }

    public function getMac() {
        return $this->mac;
    }

    public function setMac($mac) {
        $this->mac = $mac;
    }

    public function getNombreProducto() {
        return $this->nombre_producto;
    }

    public function setNombreProducto($nombre_producto) {
        $this->nombre_producto = $nombre_producto;
    }

    public function getNombreMarca() {
        return $this->nombre_marca;
    }

    public function setNombreMarca($nombre_marca) {
        $this->nombre_marca = $nombre_marca;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    public function getCodProducto() {
        return $this->cod_producto;
    }

    public function setCodProducto($cod_producto) {
        $this->cod_producto = $cod_producto;
    }

    public function getCantidadDisponible() {
        return $this->cantidad_disponible;
    }

    public function setCantidadDisponible($cantidad_disponible) {
        $this->cantidad_disponible = $cantidad_disponible;
    }

    public function getNombreBodega() {
        return $this->nombre_bodega;
    }

    public function setNombreBodega($nombre_bodega) {
        $this->nombre_bodega = $nombre_bodega;
    }

    public function getCodBodega() {
        return $this->cod_bodega;
    }

    public function setCodBodega($cod_bodega) {
        $this->cod_bodega = $cod_bodega;
    }

    public function toArray() {
        return [
            'id_item' => $this->id_item,
            'serial' => $this->serial,
            'mac' => $this->mac,
            'nombre_producto' => $this->nombre_producto,
            'nombre_marca' => $this->nombre_marca,
            'referencia' => $this->referencia,
            'cod_producto' => $this->cod_producto,
            'cantidad_disponible' => $this->cantidad_disponible,
            'nombre_bodega' => $this->nombre_bodega,
            'cod_bodega' => $this->cod_bodega
        ];
    }
}
