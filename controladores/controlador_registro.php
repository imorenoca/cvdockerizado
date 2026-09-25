<?php
require_once __DIR__ . '/../config/conexiondb.php';
require_once __DIR__ . '/../config/variablesentorno.php';
require_once __DIR__ . '/../modelos/usuariomodelo.php';


if (!empty($_POST["btnregistro"])) {
    if (!empty($_POST["usuario"]) && !empty($_POST["password"]) && !empty($_POST["email"])) {
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $email = $_POST["email"];

        $usuarioModel = new UsuarioModelo();
        $usuarioExistente = $usuarioModel->verificarUsuarioExistente($usuario, $email);

        if ($usuarioExistente) {
            // El usuario ya está registrado, mostrar mensaje de error con Bootstrap
            echo '<div class="alert alert-danger text-center" role="alert">
                      El usuario ya existe.
                  </div>';
        } else {
            // El usuario no existe, proceder con el registro
            $mensaje = $usuarioModel->registrarUsuario($usuario, $email, $password);

            // Manejo de la respuesta del modelo (mensaje de éxito o error)
            if ($mensaje === "Usuario registrado exitosamente") {
                // Mensaje de éxito con Bootstrap
                header("Location: /login");
                exit();              
            } else {
                // Mensaje de error con Bootstrap
                echo '<div class="alert alert-danger text-center" role="alert">
                          Error al registrar el usuario.
                      </div>';
            }
        }
    } else {
        // Mensaje de error si los campos están vacíos con Bootstrap
        echo '<div class="alert alert-danger" role="alert">
                  Por favor, complete todos los campos.
              </div>';
    }
}
