<?php
// Forzamos la ruta absoluta al bootstrap
require_once __DIR__ . '/includes/utils/bootstrap.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $main_css; ?>">
    <title>Feel BiG - <?php echo $pageConfig['title']; ?></title>
</head>
<body>
    <div class="body-section">
        <?php 
            render_page_layout_manager($page, $pageConfig, $auth_pages); 
        ?>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>