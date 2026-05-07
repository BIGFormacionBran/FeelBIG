<?php
require_once 'includes/managers/content_manager.php';
require_once 'includes/utils/card_render_util.php';

$contentManager = new ContentManager();

// 1. Buscamos la info de la categoría directamente por el slug
$currentCat = $contentManager->contenidoDao->get_categoria_por_slug($page);

if (!$currentCat) {
    echo "Categoría no encontrada.";
    return;
}

// 2. Traemos los items usando el slug para que el manager decida si son subcats o contenidos
$items = $contentManager->get_items_by_category_slug($page);
?>

<div class="container-page">
    <div class="section-header">
        <h1><?php echo htmlspecialchars($currentCat['nombre']); ?></h1>
        <p class="subtitle"><?php echo htmlspecialchars($currentCat['descripcion'] ?? ''); ?></p>
    </div>

    <?php if (!empty($items)): ?>
        <div class="category-grid-layout">
            <?php foreach ($items as $item): ?>
                <?php render_card_item_util($item); ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="content-placeholder">
            <p>No hay contenidos disponibles en esta sección.</p>
        </div>
    <?php endif; ?>
</div>