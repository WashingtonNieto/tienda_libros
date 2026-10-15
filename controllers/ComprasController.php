<?php
require_once 'models/Compra.php';
require_once 'models/Proveedor.php';
require_once 'models/Libro.php';

class ComprasController {
    private $model;
    private $provModel;
    private $libroModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
            $_SESSION['flash_error'] = 'Acceso Restringido: Módulo exclusivo para Administradores.';
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->model = new Compra();
        $this->provModel = new Proveedor();
        $this->libroModel = new Libro();
    }

    public function index() {
        $compras = $this->model->getAll();
        $proveedores = $this->provModel->getAll();
        $libros = $this->libroModel->getAll();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/compras/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proveedor_id   = (int)$_POST['proveedor_id'];
            $numero_factura = trim($_POST['numero_factura']);
            $libros_ids     = $_POST['libro_id'] ?? [];
            $cantidades     = $_POST['cantidad'] ?? [];
            $precios        = $_POST['precio_unitario'] ?? [];

            if (empty($libros_ids) || empty($numero_factura)) {
                $_SESSION['flash_error'] = 'Debe ingresar una factura y al menos un libro.';
                header('Location: ' . BASE_URL . '?c=compras');
                exit;
            }

            $total = 0;
            $details = [];

            for ($i = 0; $i < count($libros_ids); $i++) {
                $subtotal = (int)$cantidades[$i] * (float)$precios[$i];
                $total += $subtotal;
                $details[] = [
                    'libro_id'        => (int)$libros_ids[$i],
                    'cantidad'        => (int)$cantidades[$i],
                    'precio_unitario' => (float)$precios[$i],
                    'subtotal'        => $subtotal
                ];
            }

            $header = [
                ':proveedor_id'   => $proveedor_id,
                ':usuario_id'     => $_SESSION['user_id'],
                ':numero_factura' => $numero_factura,
                ':total'          => $total
            ];

            $res = $this->model->registrarCompra($header, $details);
            $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Compra e inventario registrados correctamente.' : 'Falló la transacción de compra.';
        }
        header('Location: ' . BASE_URL . '?c=compras');
        exit;
    }
}