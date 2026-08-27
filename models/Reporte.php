<?php
require_once __DIR__ . '/../config/database.php';

class Reporte {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener ventas agrupadas por año y mes
    public function getVentasMensuales(?int $anio = null, ?int $mes = null): array {
        $sql = "SELECT 
                    YEAR(v.fecha_venta) AS anio,
                    MONTH(v.fecha_venta) AS mes_num,
                    DATE_FORMAT(v.fecha_venta, '%M %Y') AS periodo,
                    COUNT(DISTINCT v.id) AS total_transacciones,
                    SUM(dv.cantidad) AS total_libros_vendidos,
                    SUM(v.total) AS ingresos_totales
                FROM ventas v
                INNER JOIN detalle_ventas dv ON v.id = dv.venta_id
                WHERE 1=1";

        $params = [];

        if ($anio) {
            $sql .= " AND YEAR(v.fecha_venta) = :anio";
            $params[':anio'] = $anio;
        }

        if ($mes) {
            $sql .= " AND MONTH(v.fecha_venta) = :mes";
            $params[':mes'] = $mes;
        }

        $sql .= " GROUP BY YEAR(v.fecha_venta), MONTH(v.fecha_venta)
                  ORDER BY anio DESC, mes_num DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Métricas generales (KPIs) para la parte superior del módulo
    public function getResumenGeneral(): array {
        $sql = "SELECT 
                    COALESCE(SUM(total), 0) AS total_ventas_historico,
                    COALESCE(COUNT(id), 0) AS total_facturas,
                    (SELECT COALESCE(SUM(cantidad), 0) FROM detalle_ventas) AS total_unidades_vendidas
                FROM ventas";
        return $this->db->query($sql)->fetch();
    }
}
