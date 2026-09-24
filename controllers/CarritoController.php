<?php
require_once 'models/Libro.php';
require_once 'models/Categoria.php';
require_once 'models/Cliente.php';
require_once 'models/Venta.php';

class CarritoController {
    private Libro $libroModel;
    private Categoria $catModel;
    private Cliente $clienteModel;
    private Venta $ventaModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        if ($_SESSION['rol'] !== 'Cliente') {
            $_SESSION['flash_error'] = 'Acceso Restringido: El carrito de compras es exclusivo para clientes.';
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->libroModel   = new Libro();
        $this->catModel     = new Categoria();
        $this->clienteModel = new Cliente();
        $this->ventaModel   = new Venta();

        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Catálogo de libros disponibles para comprar
    public function catalogo() {
        $busqueda    = trim($_GET['q'] ?? '');
        $categoriaId = (int)($_GET['categoria_id'] ?? 0);

        $libros      = $this->libroModel->getDisponibles($busqueda, $categoriaId);
        $categorias  = $this->catModel->getAll();

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/carrito/catalogo.php';
        require_once 'views/layouts/footer.php';
    }

    // Agrega (o incrementa) un libro en el carrito de la sesión
    public function agregar() {
        $libroId  = (int)($_POST['libro_id'] ?? 0);
        $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));

        $libro = $libroId > 0 ? $this->libroModel->getById($libroId) : null;

        if (!$libro || (int)$libro['estado'] !== 1) {
            $_SESSION['flash_error'] = 'El libro seleccionado no está disponible.';
        } else {
            $enCarrito = $_SESSION['carrito'][$libroId] ?? 0;
            $nuevaCantidad = $enCarrito + $cantidad;

            if ($nuevaCantidad > (int)$libro['stock']) {
                $_SESSION['flash_error'] = 'No hay stock suficiente de "' . $libro['titulo'] . '". Disponible: ' . $libro['stock'] . '.';
            } else {
                $_SESSION['carrito'][$libroId] = $nuevaCantidad;
                $_SESSION['flash_success'] = 'Se agregó "' . $libro['titulo'] . '" al carrito.';
            }
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '?c=carrito&a=catalogo');
        header('Location: ' . $referer);
        exit;
    }

    // Muestra el contenido del carrito
    public function index() {
        $items = $this->obtenerItemsCarrito();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/carrito/index.php';
        require_once 'views/layouts/footer.php';
    }

    // Actualiza la cantidad de un ítem del carrito
    public function actualizar() {
        $libroId  = (int)($_POST['libro_id'] ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 0);

        if ($libroId > 0 && isset($_SESSION['carrito'][$libroId])) {
            if ($cantidad <= 0) {
                unset($_SESSION['carrito'][$libroId]);
            } else {
                $libro = $this->libroModel->getById($libroId);
                if ($libro && $cantidad > (int)$libro['stock']) {
                    $_SESSION['flash_error'] = 'No hay stock suficiente de "' . $libro['titulo'] . '". Disponible: ' . $libro['stock'] . '.';
                    $cantidad = (int)$libro['stock'];
                }
                $_SESSION['carrito'][$libroId] = $cantidad;
            }
        }

        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }

    // Elimina un ítem del carrito
    public function eliminar() {
        $libroId = (int)($_GET['libro_id'] ?? 0);
        unset($_SESSION['carrito'][$libroId]);
        $_SESSION['flash_success'] = 'Libro eliminado del carrito.';
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }

    // Finaliza la compra: registra la venta y descuenta stock de forma transaccional
    public function checkout() {
        $items = $this->obtenerItemsCarrito();

        if (empty($items)) {
            $_SESSION['flash_error'] = 'Tu carrito está vacío.';
            header('Location: ' . BASE_URL . '?c=carrito');
            exit;
        }

        $cliente = $this->clienteModel->findByUsuarioId((int)$_SESSION['user_id']);
        if (!$cliente) {
            $_SESSION['flash_error'] = 'No se encontró tu perfil de cliente. Contacta con soporte.';
            header('Location: ' . BASE_URL . '?c=carrito');
            exit;
        }

        $total = 0;
        $details = [];
        foreach ($items as $item) {
            $subtotal = $item['cantidad'] * (float)$item['precio_venta'];
            $total += $subtotal;
            $details[] = [
                'libro_id'        => (int)$item['id'],
                'cantidad'        => (int)$item['cantidad'],
                'precio_unitario' => (float)$item['precio_venta'],
                'subtotal'        => $subtotal
            ];
        }

        $header = [
            ':cliente_id'     => $cliente['id'],
            ':usuario_id'     => $_SESSION['user_id'],
            ':numero_factura' => 'FAC-WEB-' . strtoupper(substr(uniqid(), -8)),
            ':total'          => $total
        ];

        $res = $this->ventaModel->registrarVenta($header, $details);

        if ($res) {
            $_SESSION['carrito'] = [];
            $_SESSION['flash_success'] = '¡Compra realizada con éxito! Gracias por tu pedido.';
        }
        // Si falla, Venta::registrarVenta ya deja el flash_error correspondiente (ej. stock insuficiente)

        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }

    // Cruza el carrito en sesión con los datos actuales de los libros (precio/stock/portada)
    private function obtenerItemsCarrito(): array {
        $items = [];
        if (empty($_SESSION['carrito'])) {
            return $items;
        }

        foreach ($_SESSION['carrito'] as $libroId => $cantidad) {
            $libro = $this->libroModel->getById((int)$libroId);
            if (!$libro || (int)$libro['estado'] !== 1) {
                unset($_SESSION['carrito'][$libroId]);
                continue;
            }
            $cantidad = min((int)$cantidad, (int)$libro['stock']);
            if ($cantidad <= 0) {
                unset($_SESSION['carrito'][$libroId]);
                continue;
            }
            $_SESSION['carrito'][$libroId] = $cantidad;

            $libro['cantidad'] = $cantidad;
            $libro['subtotal'] = $cantidad * (float)$libro['precio_venta'];
            $items[$libroId] = $libro;
        }

        return $items;
    }
}
