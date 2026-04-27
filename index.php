<?php
session_start();

// 1. Cargar Utilidades Críticas
require_once 'includes/utils/router_engine.php';
$firma_file = 'includes/utils/firma.php';
if (file_exists($firma_file)) include_once $firma_file;

// 2. Configuración de la página actual
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$auth_pages = ['login', 'registro'];

// 3. Seguridad
if (!isset($_SESSION['user_id']) && !in_array($page, $auth_pages)) {
    header("Location: index.php?page=login");
    exit();
}

// 4. Obtener automáticamente la info de la página
$pageConfig = getPageConfig($page);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title>Feel BiG - <?php echo $pageConfig['title']; ?></title>
</head>
<body>
    <div class="body-section">
        <?php
        if (in_array($page, $auth_pages)) {
            include 'includes/pages/auth_view.php';
        } else {
            include 'includes/components/header.php';

            // MIGA DE PAN AUTOMÁTICA: Solo si no es la Home
            if (!$pageConfig['is_root']) {
                include 'includes/components/breadcrumbs.php';
            }

            // CARGA DE CONTENIDO AUTOMÁTICA
            include $pageConfig['path'];

            include 'includes/components/footer.php';
        }
        ?>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>