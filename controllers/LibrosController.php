<?php
require_once 'models/Libro.php';
require_once 'models/Categoria.php';

class LibrosController {
    private Libro $model;
    private Categoria $catModel;

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
        $this->model = new Libro();
        $this->catModel = new Categoria();
    }

    public function index() {
        $libros = $this->model->getAll();
        $categorias = $this->catModel->getAll();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/libros/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $caratulaNombre = $_POST['caratula_actual'] ?? 'default_cover.jpg';

            // Carga de Imagen
            if (isset($_FILES['caratula']) && $_FILES['caratula']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['caratula']['name'], PATHINFO_EXTENSION);
                $caratulaNombre = uniqid('cover_') . '.' . $ext;
                $targetDir = 'public/uploads/covers/' . $caratulaNombre;
                move_uploaded_file($_FILES['caratula']['tmp_name'], $targetDir);
            }

            $data = [
                'categoria_id'  => (int)$_POST['categoria_id'],
                'isbn'          => trim($_POST['isbn']),
                'titulo'        => trim($_POST['titulo']),
                'autor'         => trim($_POST['autor']),
                'precio_compra' => (float)$_POST['precio_compra'],
                'precio_venta'  => (float)$_POST['precio_venta'],
                'caratula'      => $caratulaNombre,
                'destacado'     => isset($_POST['destacado']) ? 1 : 0
            ];

            if ($id) {
                $data['id'] = $id;
                $res = $this->model->update($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Libro actualizado.' : 'Error al actualizar.';
            } else {
                $data['stock'] = (int)($_POST['stock'] ?? 0);
                $res = $this->model->create($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Libro registrado con éxito.' : 'Error al registrar.';
            }
        }
        header('Location: ' . BASE_URL . '?c=libros');
        exit;
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $res = $this->model->delete($id);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Libro eliminado.' : 'Error al eliminar.';
            } catch (Exception $e) {
                $_SESSION['flash_error'] = 'No se puede eliminar el libro porque está asociado a compras o ventas.';
            }
        }
        header('Location: ' . BASE_URL . '?c=libros');
        exit;
    }
}
