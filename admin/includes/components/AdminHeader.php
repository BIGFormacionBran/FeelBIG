<?php
?>
<header style="background: #1a1a1a; color: #159BD7; padding: 1rem; border-bottom: 2px solid #159BD7;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0;">Feel BiG <span style="color: #fff; font-size: 0.8em;">| CMS Panel</span></h2>
        <nav>
            <span style="color: #eee; margin-right: 15px;">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
            <a href="/logout" style="color: #ff4d4d; text-decoration: none; font-weight: bold;">Cerrar Sesión</a>
        </nav>
    </div>
</header>