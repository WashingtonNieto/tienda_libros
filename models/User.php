<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db; // Se retiró 'PDO' para asegurar compatibilidad total

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Buscar usuario por email incluyendo el nombre del rol
    public function findByEmail($email) {
        $sql = "SELECT u.*, r.nombre AS rol 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = :email AND u.estado = 1 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ? $user : null;
    }

    // Obtener todos los roles para el formulario de registro
    public function getRoles() {
        $sql = "SELECT id, nombre FROM roles ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Registrar un nuevo usuario con BCRYPT
    public function create(array $data) {
        $sql = "INSERT INTO usuarios (rol_id, nombre, email, password, estado) 
                VALUES (:rol_id, :nombre, :email, :password, 1)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':rol_id'   => $data['rol_id'],
            ':nombre'   => $data['nombre'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }
}