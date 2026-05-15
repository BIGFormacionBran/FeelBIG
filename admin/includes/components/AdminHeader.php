<?php
?>
<header style="background: #1a1a1a; color: #159BD7; padding: 1rem; border-bottom: 2px solid #159BD7;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0;">
            <a href="/admin" style="color: #159BD7; text-decoration: none;">
                Feel BiG <span style="color: #fff; font-size: 0.8em;">| CMS Panel</span>
            </a>
        </h2>
        <nav style="display: flex; align-items: center;">
            <div style="margin-right: 20px;">
                <a href="/admin/categories" style="color: #eee; text-decoration: none; font-size: 0.9em; margin-right: 15px;">Categorías</a>
                </div>
            <span style="color: #eee; margin-right: 15px; border-left: 1px solid #444; padding-left: 15px;">
                Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
            </span>
            <a href="/Logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: bold; background: #333; padding: 5px 12px; border-radius: 4px; font-size: 0.8em;">Cerrar Sesión</a>
        </nav>
    </div>
</header>