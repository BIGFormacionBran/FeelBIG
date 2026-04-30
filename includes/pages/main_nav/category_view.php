<?php
require_once 'includes/managers/main_manager.php';
require_once 'includes/utils/card_render_util.php';

$manager = new MainManager();

// 1. Obtenemos la info de la categoría actual usando el slug ($page)
// Asumimos que get_main_menu() ya trae 'descripcion' de la DB
$menu = $manager->get_main_menu();
$currentCat = null;

foreach ($menu as $cat) {
    if ($cat['slug'] === $page) {
        $currentCat = $cat;
        break;
    }
}

if (!$currentCat) {
    echo "Categoría no encontrada.";
    return;
}

// 2. Traemos los items filtrados por el nombre real de la categoría
$items = $manager->get_items_by_category_name($currentCat['title']);
?>

<div class="container-page">
    <div class="section-header">
        <h1><?php echo htmlspecialchars($currentCat['title']); ?></h1>
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