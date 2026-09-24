<?php
require_once __DIR__ . '/../config/database.php';

class Compra {
    private $db; // Se retiró 'PDO' para asegurar compatibilidad total

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT c.*, p.razon_social AS proveedor, u.nombre AS usuario 
                FROM compras c 
                INNER JOIN proveedores p ON c.proveedor_id = p.id 
                INNER JOIN usuarios u ON c.usuario_id = u.id 
                ORDER BY c.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // REGISTRO DE COMPRA MEDIANTE TRANSACCIÓN ACID
    public function registrarCompra(array $headerData, array $details) {
        try {
            $this->db->beginTransaction();

            // 1. Insertar Cabecera de Compra
            $sqlCompra = "INSERT INTO compras (proveedor_id, usuario_id, numero_factura, total) 
                          VALUES (:proveedor_id, :usuario_id, :numero_factura, :total)";
            $stmtCompra = $this->db->prepare($sqlCompra);
            $stmtCompra->execute($headerData);
            
            $compraId = $this->db->lastInsertId();

            // 2. Insertar Detalle e Incrementar Stock
            $sqlDetalle = "INSERT INTO detalle_compras (compra_id, libro_id, cantidad, precio_unitario, subtotal) 
                           VALUES (:compra_id, :libro_id, :cantidad, :precio_unitario, :subtotal)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            $sqlStock = "UPDATE libros SET stock = stock + :cantidad, precio_compra = :precio_unitario WHERE id = :libro_id";
            $stmtStock = $this->db->prepare($sqlStock);

            foreach ($details as $item) {
                $stmtDetalle->execute([
                    ':compra_id'       => $compraId,
                    ':libro_id'        => $item['libro_id'],
                    ':cantidad'        => $item['cantidad'],
                    ':precio_unitario' => $item['precio_unitario'],
                    ':subtotal'        => $item['subtotal']
                ]);

                $stmtStock->execute([
                    ':cantidad'        => $item['cantidad'],
                    ':precio_unitario' => $item['precio_unitario'],
                    ':libro_id'        => $item['libro_id']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}