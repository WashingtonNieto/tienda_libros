<?php
require_once 'models/User.php';
require_once 'models/Cliente.php';

class AuthController {
    private User $userModel;
    private Cliente $clienteModel;

    public function __construct() {
        $this->userModel = new User();
        $this->clienteModel = new Cliente();
    }

    // Muestra la vista de Login
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/auth/login.php';
        require_once 'views/layouts/footer.php';
    }


    // Muestra el formulario de registro de usuario
    public function register() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        try {
            $roles = $this->userModel->getRoles();
        } catch (Exception $e) {
            $roles = [];
        }

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/auth/register.php';
        require_once 'views/layouts/footer.php';
    }


    // Procesa la inserción del nuevo usuario
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?c=auth&a=register');
            exit;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol_id   = (int)($_POST['rol_id'] ?? 0);

        // Validaciones del servidor
        if (empty($nombre) || empty($email) || empty($password) || $rol_id === 0) {
            $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . BASE_URL . '?c=auth&a=register');
            exit;
        }

        // Verificar si el correo ya existe
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['flash_error'] = 'El correo electrónico ya se encuentra registrado.';
            header('Location: ' . BASE_URL . '?c=auth&a=register');
            exit;
        }

        // Si el rol elegido es "Cliente" se debe registrar también el perfil de cliente
        $esCliente = $this->userModel->getRoleName($rol_id) === 'Cliente';
        $documento = trim($_POST['documento'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if ($esCliente) {
            if (empty($documento)) {
                $_SESSION['flash_error'] = 'El documento es obligatorio para registrarte como cliente.';
                header('Location: ' . BASE_URL . '?c=auth&a=register');
                exit;
            }
            if ($this->clienteModel->findByDocumento($documento)) {
                $_SESSION['flash_error'] = 'Ya existe un cliente registrado con ese documento.';
                header('Location: ' . BASE_URL . '?c=auth&a=register');
                exit;
            }
        }

        // Ejecutar registro
        $nuevoUsuarioId = $this->userModel->create([
            'rol_id'   => $rol_id,
            'nombre'   => $nombre,
            'email'    => $email,
            'password' => $password
        ]);

        if ($nuevoUsuarioId) {
            if ($esCliente) {
                $this->clienteModel->createFromUsuario([
                    ':usuario_id' => $nuevoUsuarioId,
                    ':documento'  => $documento,
                    ':nombre'     => $nombre,
                    ':email'      => $email,
                    ':telefono'   => $telefono ?: null,
                    ':direccion'  => $direccion ?: null
                ]);
            }
            $_SESSION['flash_success'] = 'Usuario registrado con éxito. Ya puedes iniciar sesión.';
            header('Location: ' . BASE_URL . '?c=auth&a=login');
        } else {
            $_SESSION['flash_error'] = 'Error al intentar registrar el usuario.';
            header('Location: ' . BASE_URL . '?c=auth&a=register');
        }
        exit;
    }


    // Procesa el formulario de Login
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Por favor ingresa todos los campos.';
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Guardar datos en sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre']  = $user['nombre'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['rol_id']  = $user['rol_id'];
            $_SESSION['rol']     = $user['rol'];

            $_SESSION['flash_success'] = '¡Bienvenido(a), ' . $user['nombre'] . '!';
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $_SESSION['flash_error'] = 'Credenciales incorrectas o usuario inactivo.';
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
    }

    // Cierra la sesión
    public function logout() {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['flash_success'] = 'Has cerrado sesión correctamente.';
        header('Location: ' . BASE_URL . '?c=auth&a=login');
        exit;
    }
}