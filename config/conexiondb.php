<?php
include_once(__DIR__ . '/variablesentorno.php');
class ConexionDb {
    private $pdo;

    public function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8mb4";
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            error_log("Error de conexión PDO a la base de datos: " . $e->getMessage());
            // Ocultar el mensaje de error al usuario
            http_response_code(500);
            exit("Error. Por favor, inténtelo de nuevo más tarde.");
            
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function closeConnection() {
        $this->pdo = null;
    }
}
           