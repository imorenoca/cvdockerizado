<?php
$rutas = [
    '/' => __DIR__ .'/vistas/bienvenida.php',
];

$ruta_solicitada = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (array_key_exists($ruta_solicitada, $rutas)) {
    require_once $rutas[$ruta_solicitada];
} else {
    require_once __DIR__ . '/vistas/error404.php';
}
