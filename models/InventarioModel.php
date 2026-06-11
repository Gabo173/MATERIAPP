<?php
/**
 * Modelo Inventario
 * Acceso a datos de la tabla inventario
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'entities/Inventario.php';

class InventarioModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM inventario ORDER BY nombre_producto";
        $stmt = $this->db->query($sql);
        
        $inventario = [];
        while ($data = $stmt->fetch()) {
            $inventario[] = new Inventario($data);
        }
        
        return $inventario;
    }

    public function getAllConStock() {
        $sql = "SELECT * FROM inventario WHERE cantidad_disponible > 0 ORDER BY nombre_producto";
        $stmt = $this->db->query($sql);
        
        $inventario = [];
        while ($data = $stmt->fetch()) {
            $inventario[] = new Inventario($data);
        }
        
        return $inventario;
    }

    public function findById($id) {
        $sql = "SELECT * FROM inventario WHERE id_item = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? new Inventario($data) : null;
    }

    public function findByBodega($bodega) {
        $sql = "SELECT * FROM inventario WHERE nombre_bodega = :bodega ORDER BY nombre_producto";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':bodega', $bodega);
        $stmt->execute();
        
        $inventario = [];
        while ($data = $stmt->fetch()) {
            $inventario[] = new Inventario($data);
        }
        
        return $inventario;
    }

    public function getBodegas() {
        $sql = "SELECT DISTINCT nombre_bodega FROM inventario ORDER BY nombre_bodega";
        $stmt = $this->db->query($sql);
        
        $bodegas = [];
        while ($data = $stmt->fetch()) {
            $bodegas[] = $data['nombre_bodega'];
        }
        
        return $bodegas;
    }

public function updateStock($idItem, $cantidad, $operacion = 'restar') {
        if ($operacion === 'restar') {
            $sql = "UPDATE inventario SET cantidad_disponible = cantidad_disponible - :cantidad 
                    WHERE id_item = :id AND cantidad_disponible >= :cantidad_min";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $idItem, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad_min', $cantidad, PDO::PARAM_INT);
        } else {
            $sql = "UPDATE inventario SET cantidad_disponible = cantidad_disponible + :cantidad 
                    WHERE id_item = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $idItem, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        }
        
        return $stmt->execute();
    }

    public function checkStock($idItem, $cantidad) {
        $sql = "SELECT cantidad_disponible FROM inventario WHERE id_item = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $idItem);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data && $data['cantidad_disponible'] >= $cantidad;
    }

    public function getStockById($idItem) {
        $sql = "SELECT cantidad_disponible FROM inventario WHERE id_item = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $idItem);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? $data['cantidad_disponible'] : 0;
    }

    public function create(Inventario $item) {
        $sql = "INSERT INTO inventario (serial, mac, nombre_producto, nombre_marca, referencia, 
                cod_producto, cantidad_disponible, nombre_bodega, cod_bodega) 
                VALUES (:serial, :mac, :nombre_producto, :nombre_marca, :referencia, 
                :cod_producto, :cantidad_disponible, :nombre_bodega, :cod_bodega)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':serial', $item->getSerial());
        $stmt->bindValue(':mac', $item->getMac());
        $stmt->bindValue(':nombre_producto', $item->getNombreProducto());
        $stmt->bindValue(':nombre_marca', $item->getNombreMarca());
        $stmt->bindValue(':referencia', $item->getReferencia());
        $stmt->bindValue(':cod_producto', $item->getCodProducto());
        $stmt->bindValue(':cantidad_disponible', $item->getCantidadDisponible());
        $stmt->bindValue(':nombre_bodega', $item->getNombreBodega());
        $stmt->bindValue(':cod_bodega', $item->getCodBodega());
        
        return $stmt->execute();
    }

    public function update(Inventario $item) {
        $sql = "UPDATE inventario SET 
                serial = :serial,
                mac = :mac,
                nombre_producto = :nombre_producto,
                nombre_marca = :nombre_marca,
                referencia = :referencia,
                cod_producto = :cod_producto,
                cantidad_disponible = :cantidad_disponible,
                nombre_bodega = :nombre_bodega,
                cod_bodega = :cod_bodega
                WHERE id_item = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $item->getIdItem());
        $stmt->bindValue(':serial', $item->getSerial());
        $stmt->bindValue(':mac', $item->getMac());
        $stmt->bindValue(':nombre_producto', $item->getNombreProducto());
        $stmt->bindValue(':nombre_marca', $item->getNombreMarca());
        $stmt->bindValue(':referencia', $item->getReferencia());
        $stmt->bindValue(':cod_producto', $item->getCodProducto());
        $stmt->bindValue(':cantidad_disponible', $item->getCantidadDisponible());
        $stmt->bindValue(':nombre_bodega', $item->getNombreBodega());
        $stmt->bindValue(':cod_bodega', $item->getCodBodega());
        
return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM inventario WHERE id_item = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
}
