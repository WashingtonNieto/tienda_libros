<?php
require_once 'models/Libro.php';
require_once 'models/Categoria.php';

class CarritoController {
    private $libroModel; // Se retiró 'Libro' para mantener compatibilidad con PHP < 7.4
    private $catModel;   // Se retiró 'Categoria' para mantener compatibilidad con PHP < 7.4

    public function __construct() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        $this->libroModel = new Libro();
        $this->catModel   = new Categoria();
    }

    // Muestra el catálogo de libros accesible para el cliente
    public function catalogo() {
        $busqueda = trim($_GET['busqueda'] ?? '');
        $categoria_id = (int)($_GET['categoria_id'] ?? 0);

        $libros = $this->libroModel->getAll();
        $categorias = $this->catModel->getAll();

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/carrito/catalogo.php';
        require_once 'views/layouts/footer.php';
    }

    // Muestra el carrito de compras del cliente
    public function index() {
        $carrito = $_SESSION['carrito'];
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['subtotal'];
        }

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/carrito/index.php';
        require_once 'views/layouts/footer.php';
    }

    // Agregar un libro al carrito
    public function add() {
        $id = (int)($_POST['libro_id'] ?? $_GET['id'] ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 1);

        if ($id > 0) {
            $libros = $this->libroModel->getAll();
            $libro = null;
            foreach ($libros as $l) {
                if ($l['id'] == $id) {
                    $libro = $l;
                    break;
                }
            }

            if ($libro) {
                if (isset($_SESSION['carrito'][$id])) {
                    $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
                    $_SESSION['carrito'][$id]['subtotal'] = $_SESSION['carrito'][$id]['cantidad'] * $_SESSION['carrito'][$id]['precio'];
                } else {
                    $_SESSION['carrito'][$id] = [
                        'id'       => $libro['id'],
                        'titulo'   => $libro['titulo'],
                        'precio'   => $libro['precio_venta'],
                        'cantidad' => $cantidad,
                        'subtotal' => $libro['precio_venta'] * $cantidad,
                        'caratula' => $libro['caratula']
                    ];
                }
                $_SESSION['flash_success'] = 'Libro agregado al carrito correctamente.';
            }
        }
        header('Location: ' . BASE_URL . '?c=carrito&a=catalogo');
        exit;
    }

    // Eliminar un ítem del carrito
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
            $_SESSION['flash_success'] = 'Producto retirado del carrito.';
        }
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }

    // Vaciar todo el carrito
    public function clear() {
        $_SESSION['carrito'] = [];
        $_SESSION['flash_success'] = 'Carrito vaciado correctamente.';
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }
}