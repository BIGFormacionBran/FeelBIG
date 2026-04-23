<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title>Feel BiG</title>
</head>
<body>
    <div class="body-section">
        <?php
        session_start();
        
        // Cargamos la firma desde tu carpeta utils
        $firma_file = 'includes/utils/firma.php';
        if (file_exists($firma_file)) {
            include_once $firma_file;
        }

        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        $auth_pages = ['login', 'registro'];

        if (!isset($_SESSION['user_id']) && !in_array($page, $auth_pages)) {
            header("Location: index.php?page=login");
            exit();
        }

        if (in_array($page, $auth_pages)) {
            include 'includes/pages/auth_view.php';
        } else {
            include 'includes/components/header.php';
            $file = 'includes/pages/' . $page . '.php';
            file_exists($file) ? include $file : include 'includes/pages/home.php';
            include 'includes/components/footer.php';
        }
        ?>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>