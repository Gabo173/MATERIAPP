<?php
/**
 * Modelo Usuario
 * Acceso a datos de la tabla usuarios
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'entities/Usuario.php';

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(Usuario $usuario) {
        $sql = "INSERT INTO usuarios (cedula, nombres_completos, correo_empresarial, celular, rol, contrasena) 
                VALUES (:cedula, :nombres_completos, :correo_empresarial, :celular, :rol, :contrasena)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cedula', $usuario->getCedula());
        $stmt->bindValue(':nombres_completos', $usuario->getNombresCompletos());
        $stmt->bindValue(':correo_empresarial', $usuario->getCorreoEmpresarial());
        $stmt->bindValue(':celular', $usuario->getCelular());
        $stmt->bindValue(':rol', $usuario->getRol());
        $stmt->bindValue(':contrasena', $usuario->getContrasena());
        
        return $stmt->execute();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE correo_empresarial = :email AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? new Usuario($data) : null;
    }

    public function findById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? new Usuario($data) : null;
    }

    public function findByCedula($cedula) {
        $sql = "SELECT * FROM usuarios WHERE cedula = :cedula";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cedula', $cedula);
        $stmt->execute();
        
        $data = $stmt->fetch();
        return $data ? new Usuario($data) : null;
    }

    public function update(Usuario $usuario) {
        $sql = "UPDATE usuarios SET 
                nombres_completos = :nombres_completos,
                correo_empresarial = :correo_empresarial,
                celular = :celular,
                rol = :rol
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->bindValue(':nombres_completos', $usuario->getNombresCompletos());
        $stmt->bindValue(':correo_empresarial', $usuario->getCorreoEmpresarial());
        $stmt->bindValue(':celular', $usuario->getCelular());
        $stmt->bindValue(':rol', $usuario->getRol());
        
        return $stmt->execute();
    }

    public function updatePassword($userId, $newPassword) {
        $sql = "UPDATE usuarios SET contrasena = :contrasena WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId);
        $stmt->bindValue(':contrasena', $newPassword);
        
        return $stmt->execute();
    }

    public function getAll() {
        $sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
        $stmt = $this->db->query($sql);
        
        $usuarios = [];
        while ($data = $stmt->fetch()) {
            $usuarios[] = new Usuario($data);
        }
        
        return $usuarios;
    }

    public function getAllByRole($rol) {
        $sql = "SELECT * FROM usuarios WHERE rol = :rol AND activo = 1 ORDER BY nombres_completos";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':rol', $rol);
        $stmt->execute();
        
        $usuarios = [];
        while ($data = $stmt->fetch()) {
            $usuarios[] = new Usuario($data);
        }
        
        return $usuarios;
    }

    public function delete($id) {
        $sql = "UPDATE usuarios SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    public function hardDelete($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
}
