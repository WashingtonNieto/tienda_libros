<?php
require_once 'models/Cliente.php';

class ClientesController {
    private Cliente $model;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        $this->model = new Cliente();
    }

    public function index() {
        $clientes = $this->model->getAll();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/clientes/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $data = [
                'documento' => trim($_POST['documento']),
                'nombre'    => trim($_POST['nombre']),
                'email'     => trim($_POST['email']),
                'telefono'  => trim($_POST['telefono']),
                'direccion' => trim($_POST['direccion'])
            ];

            if ($id) {
                $data['id'] = $id;
                $res = $this->model->update($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Cliente actualizado.' : 'Error al actualizar.';
            } else {
                $res = $this->model->create($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Cliente registrado.' : 'Error al registrar cliente.';
            }
        }
        header('Location: ' . BASE_URL . '?c=clientes');
        exit;
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $res = $this->model->delete($id);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Cliente eliminado.' : 'Error al eliminar.';
            } catch (Exception $e) {
                $_SESSION['flash_error'] = 'No se puede eliminar el cliente porque tiene ventas asociadas.';
            }
        }
        header('Location: ' . BASE_URL . '?c=clientes');
        exit;
    }
}
