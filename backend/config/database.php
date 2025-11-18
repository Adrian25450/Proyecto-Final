<?php
// Configuración de conexión a la base de datos

class Database {
    private $host = "mysql";  // Nombre del servicio en docker-compose
    private $database_name = "gestion_documentos";
    private $username = "root";
    private $password = "root123";
    public $conn;

    // Obtener conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->database_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8mb4");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
