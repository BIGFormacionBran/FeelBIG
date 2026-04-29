<?php
// 1. Usar ruta absoluta para evitar errores de carga
require_once dirname(__DIR__, 2) . '/includes/managers/main_manager.php';

// 2. Traer las variables globales definidas en bootstrap.php
global $page, $routeParts;

$manager = new MainManager();

// 3. Validar que las variables existen antes de usarlas para evitar el Warning
$safe_route_parts = isset($routeParts) ? $routeParts : [];
$crumbs = $manager->get_breadcrumbs($page, $safe_route_parts);
?>
<div class="breadcrumb-container">
    <div class="breadcrumb-list">
        <?php if ($crumbs): ?>
            <?php foreach ($crumbs as $crumb): ?>
                <?php if ($crumb['link']): ?>
                    <div class="breadcrumb-item">
                        <a href="<?php echo $crumb['link']; ?>" class="enlace-personalizado"><?php echo $crumb['title']; ?></a>
                    </div>
                    <div class="breadcrumb-separator">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M9 18l6-6-6-6"></path></svg>
                    </div>
                <?php else: ?>
                    <div class="breadcrumb-item">
                        <div class="breadcrumb-current"><?php echo $crumb['title']; ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>