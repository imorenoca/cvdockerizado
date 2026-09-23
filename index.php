<?php
require_once __DIR__ . '/nucleo/Router.php';

$router = new Router();
$router->agregarRuta('/', __DIR__ . '/vistas/bienvenida.php');

$ruta_solicitada = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$vista = $router->encontrarRuta($ruta_solicitada);

if ($vista !== null) {
    require_once $vista;
} else {
    require_once __DIR__ . '/vistas/error404.php';
}
