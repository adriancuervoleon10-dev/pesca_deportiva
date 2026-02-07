<?php
class PescaDB {
    private $conn;
    
    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'pesca_db');
        if ($this->conn->connect_error) {
            die("❌ Error conexión: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>
