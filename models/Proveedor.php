<?php
require_once __DIR__ . '/../config/database.php';

class Proveedor {
    private $db; // Se quitó 'PDO' para asegurar compatibilidad con PHP < 7.4

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM proveedores ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function create(array $data) {
        $stmt = $this->db->prepare("INSERT INTO proveedores (nit_rut, razon_social, contacto, telefono, email, direccion) VALUES (:nit_rut, :razon_social, :contacto, :telefono, :email, :direccion)");
        return $stmt->execute($data);
    }

    public function update(array $data) {
        $stmt = $this->db->prepare("UPDATE proveedores SET nit_rut = :nit_rut, razon_social = :razon_social, contacto = :contacto, telefono = :telefono, email = :email, direccion = :direccion WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id) {
        $stmt = $this->db->prepare("DELETE FROM proveedores WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}