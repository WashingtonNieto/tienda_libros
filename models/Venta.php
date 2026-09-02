<?php
require_once __DIR__ . '/../config/database.php';

class Venta {
    private $db; // Se quitó 'PDO' para asegurar compatibilidad con la versión de PHP

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT v.*, c.nombre AS cliente, u.nombre AS vendedor 
                FROM ventas v 
                INNER JOIN clientes c ON v.cliente_id = c.id 
                INNER JOIN usuarios u ON v.usuario_id = u.id 
                ORDER BY v.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // REGISTRO DE VENTA Y DESCUENTO DE STOCK MEDIANTE TRANSACCIÓN ACID
    public function registrarVenta(array $headerData, array $details) {
        try {
            $this->db->beginTransaction();

            // 1. Validar Stock Suficiente antes de procesar
            $sqlCheckStock = "SELECT stock, titulo FROM libros WHERE id = :id FOR UPDATE";
            $stmtCheck = $this->db->prepare($sqlCheckStock);

            foreach ($details as $item) {
                $stmtCheck->execute([':id' => $item['libro_id']]);
                $libro = $stmtCheck->fetch();

                if (!$libro || $libro['stock'] < $item['cantidad']) {
                    throw new Exception("Stock insuficiente para el libro: " . ($libro['titulo'] ?? 'Desconocido'));
                }
            }

            // 2. Insertar Cabecera de Venta
            $sqlVenta = "INSERT INTO ventas (cliente_id, usuario_id, numero_factura, total) 
                         VALUES (:cliente_id, :usuario_id, :numero_factura, :total)";
            $stmtVenta = $this->db->prepare($sqlVenta);
            $stmtVenta->execute($headerData);

            $ventaId = $this->db->lastInsertId();

            // 3. Insertar Detalle y Descontar Stock
            $sqlDetalle = "INSERT INTO detalle_ventas (venta_id, libro_id, cantidad, precio_unitario, subtotal) 
                           VALUES (:venta_id, :libro_id, :cantidad, :precio_unitario, :subtotal)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            $sqlStock = "UPDATE libros SET stock = stock - :cantidad WHERE id = :libro_id";
            $stmtStock = $this->db->prepare($sqlStock);

            foreach ($details as $item) {
                $stmtDetalle->execute([
                    ':venta_id'        => $ventaId,
                    ':libro_id'        => $item['libro_id'],
                    ':cantidad'        => $item['cantidad'],
                    ':precio_unitario' => $item['precio_unitario'],
                    ':subtotal'        => $item['subtotal']
                ]);

                $stmtStock->execute([
                    ':cantidad' => $item['cantidad'],
                    ':libro_id' => $item['libro_id']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['flash_error'] = $e->getMessage();
            return false;
        }
    }
}