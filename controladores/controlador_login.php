<?php
require_once __DIR__ . '/../config/conexiondb.php';
require_once __DIR__ . '/../config/variablesentorno.php';
require_once __DIR__ . '/../modelos/usuariomodelo.php';


if (!empty($_POST["btningresar"])) {
    if (!empty($_POST["usuario"]) and !empty($_POST["password"])) {
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $usuarioModel = new UsuarioModelo();
        $usuarioRegistrado = $usuarioModel->verificarUsuario($usuario, $password);

        if ($usuarioRegistrado) {
            // Regenerar id de sesión para prevenir ataques de fijación de sesión
            session_regenerate_id(true);
            // redirección según el rol
            $_SESSION['id_rol'] = $usuarioRegistrado['id_rol'];
            $_SESSION['id_usuario']= $usuarioRegistrado['id_usuario'];

            switch ($_SESSION['id_rol'] ) {
                case 2:
                    header("Location: /ofertas");
                    exit();
                case 1:
                    header("Location: /administrador");
                    exit();
                

                default:
                    header("Location: /login");
                    exit();
            }
        } else {
            // La verificación del usuario no fue exitosa, mostrar mensaje de error
            $_SESSION['error_login'] = 'Nombre de usuario o contraseña incorrectos.';
            header("Location: /login");
            exit();}
    } else {
        // Mensaje de error si los campos están vacíos
    
        $_SESSION['error_login'] = 'Por favor, complete todos los campos.';
        header("Location: /login");
        exit();
    }
}
    





   


