<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Buscar usuario por email incluyendo el nombre del rol
    public function findByEmail(string $email): ?array {
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


    // Obtener todos los roles activos para el desplegable del formulario
    public function getRoles(): array {
        $sql = "SELECT id, nombre FROM roles ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Registrar un nuevo usuario con contraseña hasheada. Devuelve el ID insertado o false.
    public function create(array $data) {
        $sql = "INSERT INTO usuarios (rol_id, nombre, email, password, estado)
                VALUES (:rol_id, :nombre, :email, :password, 1)";
        $stmt = $this->db->prepare($sql);

        $ok = $stmt->execute([
            ':rol_id'   => $data['rol_id'],
            ':nombre'   => $data['nombre'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    // Nombre del rol dado su id (para decidir el flujo de registro)
    public function getRoleName(int $rolId): ?string {
        $stmt = $this->db->prepare("SELECT nombre FROM roles WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $rolId]);
        $rol = $stmt->fetch();
        return $rol ? $rol['nombre'] : null;
    }

}