<?php
require_once '../config/variablesentorno.php';
require_once '../config/conexiondb.php';

class UsuarioModelo {
    private $mysqli;

    public function __construct() {

        // Crear una instancia de la clase de conexión a la base de datos
        $conexionDb = new ConexionDb();
        // Obtener la instancia de mysqli desde la clase de conexión
        $this->mysqli = $conexionDb->getMysqli();
        // Verificar si hay errores en la conexión
        if ($this->mysqli->connect_errno) {
            echo "Error de conexión: " . $this->mysqli->connect_errno;
            exit;
        }
    }

    public function verificarUsuario($usuario, $password) {

        try {
            $sql = "SELECT * FROM usuario WHERE usuario = ? AND password = SHA1(?)";
            $stmt = $this->mysqli->prepare($sql);

            // Verificar si la preparación fue exitosa
            if ($stmt) {
                $stmt->bind_param("ss", $usuario, $password);
                $stmt->execute();

                $resultado = $stmt->get_result()->fetch_assoc();

                if ($resultado) {
                    return $resultado; // Devuelve toda la información del usuario
                }
            }
        } catch (Exception $e) {
            // Manejo de excepciones


            return false; // Usuario o contraseña incorrectos


        } finally {
            // Cerrar el statement después de usarlo
            $stmt->close();
        }
    }


    public function registrarUsuario($nombre, $correo, $contrasena) {
        // Hashear la contraseña con SHA1
        $contrasenaHasheada = sha1($contrasena);

        // Establecer el valor predeterminado del rol (2 en este caso)
        $defaultRol = 2;

        $sql = "INSERT INTO usuario (usuario, correo, password, id_rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt) {
            $stmt->bind_param("sssi", $nombre, $correo, $contrasenaHasheada, $defaultRol);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return "Usuario registrado exitosamente";
            } else {
                return "Error al registrar el usuario";
            }
        }
    }
    public function verificarUsuarioExistente($usuario, $email) {
        try {
            $sql = "SELECT * FROM usuario WHERE usuario = ? OR correo = ?";
            $stmt = $this->mysqli->prepare($sql);

            // Verificar si la preparación fue exitosa
            if ($stmt) {
                $stmt->bind_param("ss", $usuario, $email);
                $stmt->execute();

                $resultado = $stmt->get_result()->fetch_assoc();

                if ($resultado) {
                    return true; // El usuario existe
                }
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            return false; // Error al verificar el usuario
        } finally {
            // Cerrar el statement después de usarlo
            $stmt->close();
        }

        return false; // El usuario no existe
    }

}
