<?php
require_once 'models/Venta.php';
require_once 'models/Cliente.php';
require_once 'models/Libro.php';

class VentasController {
    private $model;
    private $clienteModel;
    private $libroModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        if (!in_array($_SESSION['rol'], ['Administrador', 'Vendedor'], true)) {
            $_SESSION['flash_error'] = 'Acceso Restringido: Módulo exclusivo para personal de la tienda.';
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->model = new Venta();
        $this->clienteModel = new Cliente();
        $this->libroModel = new Libro();
    }

    public function index() {
        $ventas = $this->model->getAll();
        $clientes = $this->clienteModel->getAll();
        $libros = $this->libroModel->getAll();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/ventas/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_id = (int)$_POST['cliente_id'];
            $libros_ids = $_POST['libro_id'] ?? [];
            $cantidades = $_POST['cantidad'] ?? [];
            $precios    = $_POST['precio_unitario'] ?? [];

            if (empty($libros_ids) || $cliente_id === 0) {
                $_SESSION['flash_error'] = 'Debe seleccionar un cliente y al menos un libro.';
                header('Location: ' . BASE_URL . '?c=ventas');
                exit;
            }

            $numero_factura = 'FAC-VEN-' . str_pad((string)rand(1, 99999), 5, '0', STR_PAD_LEFT);
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
                ':cliente_id'      => $cliente_id,
                ':usuario_id'      => $_SESSION['user_id'],
                ':numero_factura'  => $numero_factura,
                ':total'           => $total
            ];

            $res = $this->model->registrarVenta($header, $details);
            if ($res) {
                $_SESSION['flash_success'] = 'Venta efectuada y stock actualizado correctamente.';
            }
        }
        header('Location: ' . BASE_URL . '?c=ventas');
        exit;
    }
}