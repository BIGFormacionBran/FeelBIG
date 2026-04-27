<?php 
require_once 'includes/utils/navigation_engine.php';
$menuItems = getDynamicMenu();
?>
<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="index.php?page=home">
                <img src="assets/img/logo.png" alt="Feel BiG" class="header-logo-img">
            </a>
        </div>

        <nav class="nav-menu">
            <a href="index.php?page=home" class="nav-link">Home</a>
            <?php foreach ($menuItems as $item): ?>
                <a href="index.php?page=<?php echo $item['slug']; ?>" class="nav-link">
                    <?php echo $item['title']; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="user-menu">
            <div class="user-icon-wrapper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="user-icon">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <div class="user-dropdown">
                    <a href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</header>