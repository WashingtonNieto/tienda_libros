<?php
session_start();

require_once 'config/config.php';
require_once 'config/database.php';

// Captura de Parámetros de Ruta: c = controller, a = action
$controller = isset($_GET['c']) ? ucfirst(strtolower($_GET['c'])) . 'Controller' : 'HomeController';
$action     = isset($_GET['a']) ? strtolower($_GET['a']) : 'index';

$controllerPath = 'controllers/' . $controller . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    if (class_exists($controller)) {
        $object = new $controller();
        if (method_exists($object, $action)) {
            $object->$action();
        } else {
            die("Error 404: La acción '{$action}' no existe en el controlador {$controller}.");
        }
    } else {
        die("Error 404: La clase '{$controller}' no fue encontrada.");
    }
} else {
    die("Error 404: El archivo del controlador '{$controllerPath}' no existe.");
}