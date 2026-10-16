<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $db; // Se retiró 'PDO' para asegurar compatibilidad

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function findByUsuarioId(int $usuarioId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE usuario_id = :usuario_id LIMIT 1");
        $stmt->execute([':usuario_id' => $usuarioId]);
        $cliente = $stmt->fetch();
        return $cliente ? $cliente : null;
    }

    public function findByDocumento(string $documento): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE documento = :documento LIMIT 1");
        $stmt->execute([':documento' => $documento]);
        $cliente = $stmt->fetch();
        return $cliente ? $cliente : null;
    }

    // Crea el registro de cliente asociado a una cuenta de usuario (auto-registro en la tienda online)
    public function createFromUsuario(array $data): bool {
        $sql = "INSERT INTO clientes (usuario_id, documento, nombre, email, telefono, direccion)
                VALUES (:usuario_id, :documento, :nombre, :email, :telefono, :direccion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("INSERT INTO clientes (documento, nombre, email, telefono, direccion) VALUES (:documento, :nombre, :email, :telefono, :direccion)");
        return $stmt->execute($data);
    }

    public function update(array $data) {
        $stmt = $this->db->prepare("UPDATE clientes SET documento = :documento, nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id) {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}