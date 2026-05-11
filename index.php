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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <title>Feel BiG - <?php echo $pageConfig['title']; ?></title>
</head>
<body>
    <div class="body-section">
        <?php 
            render_page_layout_manager($page, $pageConfig, $auth_pages); 
        ?>
    </div>

    <?php if (isset($needs_swiper) && $needs_swiper): ?><script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script><?php endif; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>