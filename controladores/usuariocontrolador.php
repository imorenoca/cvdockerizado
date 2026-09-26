<?php
// UsuarioController.php
require_once __DIR__ . '/../modelos/usuariomodelo.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
       $usuarioController->iniciarSesion($usuario, $contrasena);
       $usuario = $_POST["usuario"] ?? '';
       $contrasena = $_POST["contrasena"] ?? '';
       $usuarioController->iniciarSesion($usuario, $contrasena);
}

class UsuarioController
{
    private $usuarioModelo;

    public function __construct()
    {
        $this->usuarioModelo = new UsuarioModelo();
    }

    public function iniciarSesion($usuario, $contrasena)
    {
        // Validar datos (puedes agregar más validaciones según sea necesario)
        if (empty($usuario) || empty($contrasena)) {
            header("Location: ../login.php?error=1");
            exit();
        }

        // Lógica de inicio de sesión
        $usuarioRegistrado = $this->usuarioModelo->verificarUsuario($usuario, $contrasena);

        if ($usuarioRegistrado) {
            if(session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["usu"] = $usuarioRegistrado['correo'];
            $_SESSION["idRol"] = $usuarioRegistrado['rol_id'];

            // Redirigir según el rol
            $this->redirigirSegunRol($usuarioRegistrado['rol_id']);
        } else {
            // La validación de inicio de sesión falló, redirige a la página de login con un mensaje de error
            header("Location: ../login.php?error=2");
            exit();
        }
    }

    private function redirigirSegunRol($rolId)
    {
        switch ($rolId) {
            case 1:
                header("Location: ../administrador");
                exit();
            case 2:
                header("Location: ../usuario");
                exit();
            default:
                header("Location: ../login");
                exit();
        }
    }
}
