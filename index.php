<?php require_once 'includes/utils/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $main_css; ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title>Feel BiG - <?php echo $pageConfig['title']; ?></title>
</head>
<body>
    <div class="body-section">
        <?php if (in_array($page, $auth_pages)): ?>
            <?php include 'includes/pages/auth_view.php'; ?>
        <?php else: ?>
            <?php 
                include 'includes/components/header.php';
                if (!$pageConfig['is_root']) include 'includes/components/breadcrumbs.php';
                include $pageConfig['path'];
                include 'includes/components/footer.php';
            ?>
        <?php endif; ?>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>