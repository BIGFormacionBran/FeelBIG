<?php
session_start();
require_once 'includes/daos/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    echo "<script>console.log('Iniciando proceso de registro para: $correo');</script>";

    $dao = new UsuarioDAO();
    if ($dao->registrar($nombre, $correo, $pass)) {
        $user = $dao->login($correo, $pass); 
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['id_tipo_cuenta'];
            header("Location: index.php?page=home");
            exit();
        }
    }
    
    // Si llegamos aquí es que algo falló
    echo "<script>
        console.error('El registro falló. Revisa los mensajes anteriores en la consola.');
        setTimeout(function(){ 
            window.location.href = 'index.php?page=registro&error=1'; 
        }, 3000); // Pausa de 3 segundos para que puedas leer la consola antes de que redirija
    </script>";
    exit();
}