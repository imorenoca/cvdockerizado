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
            // redirección según el rol
            $_SESSION['id_rol'] = $usuarioRegistrado['id_rol'];
            $_SESSION['id_usuario']= $usuarioRegistrado['id_usuario'];

            switch ($_SESSION['id_rol'] ) {
                case 2:
                    header("Location: ../vistas/ofertas.php");
                    exit();
                case 1:
                    header("Location: ../vistas/administrador.php");
                    exit();
                // ampliar para un futuro

                default:
                    header("Location: ../vistas/login.php");
                    exit();
            }
        } else {
            // La verificación del usuario no fue exitosa, mostrar mensaje de error
            echo '<div class="alert alert-danger text-center" role="alert">
            Nombre de usuario y/o contraseña incorrecto
        </div>';
        }
    } else {
        // Mensaje de error si los campos están vacíos
    
        echo '<div class="alert alert-danger text-center" role="alert">
        Por favor, ingrese nombre de usuario y contraseña.
    </div>';
    }
}
    







