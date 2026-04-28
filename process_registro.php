<?php
session_start();
require_once 'includes/daos/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['usuario'];
    $pass = $_POST['password'];

    echo "<script>console.log('--- INICIO DEBUG REGISTRO ---');</script>";

    $dao = new UsuarioDAO();
    $resultado = $dao->registrar($nombre, $correo, $pass);

    if ($resultado) {
        echo "<script>console.log('REGISTRO COMPLETADO. Puedes volver atrás.');</script>";
    } else {
        echo "<script>console.error('EL REGISTRO NO SE PUDO REALIZAR.');</script>";
    }

    // MATAMOS EL PROCESO AQUÍ PARA QUE NO REDIRIJA Y PUEDAS VER LA CONSOLA
    die("<hr><h3>Debug Finalizado</h3><p>Revisa la consola del navegador (F12) para ver los pasos 1 al 5.</p><a href='index.php?page=registro'>Volver al formulario</a>");
}