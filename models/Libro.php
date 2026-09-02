<?php
require_once __DIR__ . '/../config/database.php';

class Libro {
    private $db; // Se quitó 'PDO' para compatibilidad con versiones antiguas de PHP

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $sql = "SELECT l.*, c.nombre AS categoria 
                FROM libros l 
                INNER JOIN categorias c ON l.categoria_id = c.id 
                ORDER BY l.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO libros (categoria_id, isbn, titulo, autor, precio_compra, precio_venta, stock, caratula, destacado) 
                VALUES (:categoria_id, :isbn, :titulo, :autor, :precio_compra, :precio_venta, :stock, :caratula, :destacado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update(array $data): bool {
        $sql = "UPDATE libros SET categoria_id = :categoria_id, isbn = :isbn, titulo = :titulo, autor = :autor, 
                precio_compra = :precio_compra, precio_venta = :precio_venta, caratula = :caratula, destacado = :destacado 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM libros WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Obtener libros marcados como destacados/recomendados
    public function getDestacados(int $limit = 6): array {
        $sql = "SELECT l.*, c.nombre AS categoria 
                FROM libros l 
                INNER JOIN categorias c ON l.categoria_id = c.id 
                WHERE l.destacado = 1 AND l.estado = 1 
                ORDER BY l.id DESC LIMIT " . (int)$limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Obtener los libros más vendidos
    public function getMasVendidos(int $limit = 6): array {
        $sql = "SELECT l.*, c.nombre AS categoria, SUM(dv.cantidad) AS total_vendidos 
                FROM detalle_ventas dv 
                INNER JOIN libros l ON dv.libro_id = l.id 
                INNER JOIN categorias c ON l.categoria_id = c.id 
                GROUP BY l.id 
                ORDER BY total_vendidos DESC LIMIT " . (int)$limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}