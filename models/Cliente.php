<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("INSERT INTO clientes (documento, nombre, email, telefono, direccion) VALUES (:documento, :nombre, :email, :telefono, :direccion)");
        return $stmt->execute($data);
    }

    public function update(array $data): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET documento = :documento, nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}