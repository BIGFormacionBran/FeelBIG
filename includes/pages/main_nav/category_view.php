<?php
require_once 'includes/managers/content_manager.php';
require_once 'includes/utils/card_render_util.php';

$contentManager = new ContentManager();

// 1. Buscamos la categoría por el slug que viene en la URL ($page)
$currentCat = $contentManager->contenidoDao->get_categoria_por_slug($page);

if (!$currentCat) {
    echo "Categoría no encontrada.";
    return;
}

// 2. Traemos los items usando el método compatible
$items = $contentManager->get_items_by_category_name($page);
?>

<div class="container-page">
    <div class="section-header">
        <h1><?php echo htmlspecialchars($currentCat['nombre']); ?></h1>
    </div>

    <?php if (!empty($items)): ?>
        <div class="category-grid-layout">
            <?php foreach ($items as $item): ?>
                <?php render_card_item_row($item); ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="content-placeholder">
            <p>No hay contenidos disponibles en esta sección.</p>
        </div>
    <?php endif; ?>
</div>