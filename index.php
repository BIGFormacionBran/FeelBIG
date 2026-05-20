<?php
require_once __DIR__ . '/includes/utils/Bootstrap.php'; 
require_once __DIR__ . '/includes/managers/UserManager.php';

$userManager = new UserManager();
$isAdmin = $userManager->isAdmin($_SESSION['user_role'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $mainCss; ?>">
    <?php if ($isAdmin): ?>
        <link rel="stylesheet" href="<?php echo $adminCss; ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Feel BiG - <?php echo $pageConfig['title']; ?></title>
</head>
<body>
    <div class="body-section">
        <?php 
            if ($isAdmin) {
                include __DIR__ . '/admin/includes/components/Header.php';
            }
            renderPageLayoutManager($page, $pageConfig, $authPages); 
        ?>
    </div>
    <?php if (isset($needsSwiper) && $needsSwiper): ?>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <?php endif; ?>
    <?php if ($isAdmin): ?>
        <script src="admin/assets/js/admin.js" defer></script>
    <?php endif; ?>
        <script src="assets/js/main.js"></script>
</body>
</html>