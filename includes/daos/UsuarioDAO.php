<?php
require_once __DIR__ . '/../utils/db_util.php';

class UsuarioDAO {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getConnection();
        } catch (Exception $e) {
            $this->db = null;
        }
    }

    public function registrar($nombre, $correo, $password, $id_tipo = 4) {
        echo "<script>console.log('3. Intentando INSERT para: $correo');</script>";
        if (!$this->db) return false;
        
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO USUARIO (nombre, correo, password, id_tipo_cuenta) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute([$nombre, $correo, $hash, $id_tipo]);
            
            echo "<script>console.log('4. Resultado del INSERT: " . ($res ? "OK" : "FALLO") . "');</script>";
            return $res;
        } catch (PDOException $e) {
            echo "<script>console.error('5. ERROR EN INSERT: " . addslashes($e->getMessage()) . "');</script>";
            return false;
        }
    }

    public function login($correo, $password) {
        if (!$this->db) throw new Exception("no_db");
        $sql = "SELECT * FROM USUARIO WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}