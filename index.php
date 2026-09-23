<?php
session_start();

require_once __DIR__ . '/nucleo/Router.php';

$enrutador = new Router();
$enrutador->agregarRuta('GET', '/', __DIR__ . '/vistas/bienvenida.php');
$enrutador->agregarRuta('GET', '/login', __DIR__ . '/vistas/login.php');
$enrutador->agregarRuta('POST', '/login', __DIR__ . '/controladores/loginController.php');
$enrutador->agregarRuta('GET', '/register', __DIR__ . '/vistas/register.php');
$enrutador->agregarRuta('POST', '/register', __DIR__ . '/controladores/registerController.php');


$ruta_solicitada = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'];
$vista = $enrutador->encontrarRuta($metodo, $ruta_solicitada);

if ($vista !== null) {
    require_once $vista;
} else {
    require_once __DIR__ . '/vistas/error404.php';
}
