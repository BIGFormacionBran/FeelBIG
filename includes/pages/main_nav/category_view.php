<?php
require_once 'includes/managers/content_manager.php';
require_once 'includes/utils/card_render_util.php';

$contentManager = new ContentManager();

// 1. Obtenemos la info de la categoría actual usando el ContentManager
$categorias = $contentManager->get_home_structure();
$currentCat = null;

foreach ($categorias as $cat) {
    $slug = str_replace(' ', '-', strtolower($cat['nombre']));
    if ($slug === $page) {
        $currentCat = $cat;
        $currentCat['slug'] = $slug;
        break;
    }
}

if (!$currentCat) {
    echo "Categoría no encontrada.";
    return;
}

// 2. Traemos los items directamente desde el responsable de contenido
$items = $contentManager->get_items_by_category_name($currentCat['nombre']);
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