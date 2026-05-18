<?php
/* Eliminamos el <link> de aquí porque ya lo inyecta el index.php mediante $adminCss */
?>
<script src="/admin/assets/js/admin.js" defer></script>

<header class="admin-main-header">
    <div class="admin-header-flex">
        <h2>
            <a href="/admin" class="admin-logo-link">
                Feel BiG <span>| CMS Panel</span>
            </a>
        </h2>
        <nav class="admin-nav-container">
            <div class="admin-nav-group">
                <a href="/admin/categories" class="admin-nav-link">Categorías</a>
                <a href="/admin/contents" class="admin-nav-link">Contenidos</a>
            </div>
            <span class="admin-user-info">
                Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
            </span>
            <a href="/Logout.php" class="admin-logout-btn">Cerrar Sesión</a>
        </nav>
    </div>
</header>