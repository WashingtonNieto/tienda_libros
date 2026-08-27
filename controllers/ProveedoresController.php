<?php
require_once 'models/Proveedor.php';

class ProveedoresController {
    private Proveedor $model;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        if ($_SESSION['rol'] !== 'Administrador') {
            $_SESSION['flash_error'] = 'Acceso denegado: Solo administradores pueden gestionar proveedores.';
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->model = new Proveedor();
    }

    public function index() {
        $proveedores = $this->model->getAll();
        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/proveedores/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $data = [
                'nit_rut'      => trim($_POST['nit_rut']),
                'razon_social' => trim($_POST['razon_social']),
                'contacto'     => trim($_POST['contacto']),
                'telefono'     => trim($_POST['telefono']),
                'email'        => trim($_POST['email']),
                'direccion'    => trim($_POST['direccion'])
            ];

            if ($id) {
                $data['id'] = $id;
                $res = $this->model->update($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Proveedor actualizado.' : 'Error al actualizar.';
            } else {
                $res = $this->model->create($data);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Proveedor registrado.' : 'Error al registrar.';
            }
        }
        header('Location: ' . BASE_URL . '?c=proveedores');
        exit;
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $res = $this->model->delete($id);
                $_SESSION[$res ? 'flash_success' : 'flash_error'] = $res ? 'Proveedor eliminado.' : 'Error al eliminar.';
            } catch (Exception $e) {
                $_SESSION['flash_error'] = 'No se puede eliminar el proveedor porque tiene compras registradas.';
            }
        }
        header('Location: ' . BASE_URL . '?c=proveedores');
        exit;
    }
}
