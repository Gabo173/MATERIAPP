<?php
/**
 * Modelo CedulaAdministrador
 * Acceso a datos de la tabla cedulas_administrador
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'entities/CedulaAdministrador.php';

class CedulaAdministradorModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function exists($cedula) {
        $sql = "SELECT COUNT(*) FROM cedulas_administrador WHERE cedula = :cedula";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cedula', $cedula);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    public function findByCedula($cedula) {
        $sql = "SELECT * FROM cedulas_administrador WHERE cedula = :cedula";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cedula', $cedula);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? new CedulaAdministrador($data) : null;
    }

    public function getAll() {
        $sql = "SELECT * FROM cedulas_administrador ORDER BY nombre_referencia";
        $stmt = $this->db->query($sql);
        
        $cedulas = [];
        while ($data = $stmt->fetch()) {
            $cedulas[] = new CedulaAdministrador($data);
        }
        
        return $cedulas;
    }

public function create(CedulaAdministrador $cedula) {
        $sql = "INSERT INTO cedulas_administrador (cedula, nombre_referencia) 
                VALUES (:cedula, :nombre_referencia)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cedula', $cedula->getCedula());
        $stmt->bindValue(':nombre_referencia', $cedula->getNombreReferencia());
        
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM cedulas_administrador WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
}
