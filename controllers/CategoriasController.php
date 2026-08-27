<?php
require_once 'models/Categoria.php';

class CategoriasController {
    private Categoria $model;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        $this->model = new Categoria();
    }

    public function index() {
        $categorias = $this->model->getAll();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/categorias/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $data = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion'])
            ];

            if ($id) {
                $res = $this->model->update($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Categoría actualizada correctamente.' : 'Error al actualizar categoría.';
            } else {
                $res = $this->model->create($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Categoría creada correctamente.' : 'Error al crear categoría.';
            }
        }
        header('Location: ' . BASE_URL . '?c=categorias');
        exit;
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $res = $this->model->delete($id);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Categoría eliminada.' : 'No se pudo eliminar.';
            } catch (Exception $e) {
                $_SESSION['flash_error'] = 'No se puede eliminar la categoría porque tiene libros asociados.';
            }
        }
        header('Location: ' . BASE_URL . '?c=categorias');
        exit;
    }
}