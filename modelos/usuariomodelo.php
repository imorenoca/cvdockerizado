<?php
require_once __DIR__ . '/../config/conexiondb.php';
require_once __DIR__ . '/modelobase.php';

class UsuarioModelo extends ModeloBase {


    public function verificarUsuario($usuario, $password) {

        try {
            $sql = "SELECT * FROM usuario WHERE usuario = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuario]);
            $resultado = $stmt->fetch();

            // Verificar si la preparación fue exitosa
            if ($stmt) {
                $stmt->bind_param("s", $usuario);
                $stmt->execute();

                $resultado = $stmt->get_result()->fetch_assoc();

                if ($resultado && password_verify($password, $resultado['password'])) {
                    return $resultado; // Devuelve toda la información del usuario
                }
            }
            return false; // Usuario o contraseña incorrectos
        } catch (PDOException $e) {
            // Manejo de excepciones -
            echo "Error en la consulta: " . $e->getMessage();
            return false; // Usuario o contraseña incorrectos
        } 
    }


    public function registrarUsuario($nombre, $correo, $contrasena) {
        // Hashear la contraseña con password_hash (bcrypt por defecto), con salt automático e incorporado en el propio hash
        $contrasenaHasheada = password_hash($contrasena, PASSWORD_DEFAULT);

        // Establecer el valor predeterminado del rol (2 en este caso)
        $defaultRol = 2;

        try {
            $sql = "INSERT INTO usuario (usuario, correo, password, id_rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $correo, $contrasenaHasheada, $defaultRol]);

            if ($stmt->rowCount() > 0) {
                return "Usuario registrado exitosamente";
            } else {
                return "Error al registrar el usuario";
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false; 
        }
    }
        
    public function verificarUsuarioExistente($usuario, $email) {
        try {
            $sql = "SELECT * FROM usuario WHERE usuario = ? OR correo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuario, $email]);
            return $stmt->fetch() !== false; // Devuelve true si el usuario existe, false si no
        } catch (PDOException $e) {
            error_log("Error al verificar usuario existente: " . $e->getMessage());
            return false;
        }
    }
  
}
