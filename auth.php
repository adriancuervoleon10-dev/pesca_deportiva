<?php
session_start();
require_once 'config.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new PescaDB();
    }
    
    public function login($username, $password) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['es_admin'] = $user['es_admin'];
            
            // Actualizar último login
            $this->db->getConnection()->query("UPDATE usuarios SET ultimo_login = NOW() WHERE id = " . $user['id']);
            return true;
        }
        return false;
    }
    
    public function isLogged() {
        return isset($_SESSION['user_id']);
    }
    
    public function requireLogin() {
        if (!$this->isLogged()) {
            header('Location: login.php');
            exit;
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: login.php');
        exit;
    }
}

$auth = new Auth();
?>
