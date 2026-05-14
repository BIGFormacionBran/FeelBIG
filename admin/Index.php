<?php
// Ya no necesitas volver a validar aquí porque Bootstrap.php lo hizo antes de incluir este archivo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración | Feel BiG</title>
    <link rel="stylesheet" href="/assets/css/style.css"> </head>
<body>
    <?php include __DIR__ . '/includes/components/AdminHeader.php'; ?>

    <div style="padding: 20px;">
        <div style="background: #fff; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
            <h1>Bienvenido al Panel de Control</h1>
            <p>Acceso exclusivo para personal autorizado (ID 1 y 2).</p>
        </div>
    </div>
</body>
</html>