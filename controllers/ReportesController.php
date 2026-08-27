<?php
require_once 'models/Reporte.php';

class ReportesController {
    private Reporte $model;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        $this->model = new Reporte();
    }

    public function index() {
        $anio = !empty($_GET['anio']) ? (int)$_GET['anio'] : null;
        $mes  = !empty($_GET['mes']) ? (int)$_GET['mes'] : null;

        $reportes = $this->model->getVentasMensuales($anio, $mes);
        $kpis     = $this->model->getResumenGeneral();

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/reportes/index.php';
        require_once 'views/layouts/footer.php';
    }
}
