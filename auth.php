<?php
session_start();
// Simulamos login exitoso
$_SESSION['user_id'] = 99; 
header("Location: index.php?page=home");
exit();
?>