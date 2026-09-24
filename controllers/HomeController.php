<?php
require_once 'models/Libro.php';

class HomeController {
    private $libroModel; // Se remueve el tipo 'Libro' para compatibilidad con PHP < 7.4

    public function __construct() {
        $this->libroModel = new Libro();
    }

    public function index() {
        $masVendidos = $this->libroModel->getMasVendidos(4);
        $destacados  = $this->libroModel->getDestacados(4);

        require_once 'views/layouts/header.php';
        require_once 'views/layouts/navbar.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }
}