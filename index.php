<?php
require_once __DIR__ . '/includes/utils/Bootstrap.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $mainCss; ?>">
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
        <link rel="stylesheet" href="<?php echo $adminCss; ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Feel BiG - <?php echo $pageConfig['title']; ?></title>
</head>
<body>
    <div class="body-section">
        <?php 
            renderPageLayoutManager($page, $pageConfig, $authPages); 
        ?>
    </div>
    <?php if (isset($needsSwiper) && $needsSwiper): ?>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <?php endif; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>