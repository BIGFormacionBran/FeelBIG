<header class="admin-main-header">
    <div class="admin-header-flex">
        <div class="admin-logo-wrapper">
            <a href="/admin" class="admin-logo-link">
                Feel BiG <span class="admin-subtitle">| CMS Panel</span>
            </a>
        </div>
        
        <nav class="admin-nav-container">
            <a href="/admin/categories" class="admin-nav-link">Categorías</a>
            
            <div class="admin-user-info">
                Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
            </div>
            
            <a href="/Logout.php" class="admin-logout-btn">Cerrar Sesión</a>
        </nav>
    </div>
</header>