<?php
require_once __DIR__ . '/../utils/db_util.php';

class UsuarioDAO {
    private $db;

    private function debug_to_console($data) {
        $output = json_encode($data);
        echo "<script>console.log('PHP Debug (UsuarioDAO): " . $output . "');</script>";
    }

    public function __construct() {
        try {
            $this->db = Database::getConnection();
        } catch (Exception $e) {
            $this->debug_to_console("Error de conexión: " . $e->getMessage());
            $this->db = null;
        }
    }

    public function registrar($nombre, $correo, $password, $id_tipo = 4) {
        $this->debug_to_console("Intentando registrar a: $correo");
        if (!$this->db) {
            $this->debug_to_console("Error: No hay instancia de conexión a la base de datos.");
            return false;
        }
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
            $this->debug_to_console("Resultado execute: " . ($res ? "True" : "False"));
            return $res;
        } catch (PDOException $e) {
            $this->debug_to_console("Excepción PDO: " . $e->getMessage());
            return false;
        }
    }

    public function login($correo, $password) {
        if (!$this->db) throw new Exception("no_db");
        $this->debug_to_console("Buscando usuario: $correo");
        
        $sql = "SELECT * FROM USUARIO WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->debug_to_console("Login exitoso.");
            return $user;
        }
        $this->debug_to_console("Login fallido o usuario no encontrado.");
        return false;
    }
}