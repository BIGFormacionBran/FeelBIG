<?php
require_once 'includes/managers/ContentManager.php';
require_once 'includes/utils/CardRenderUtil.php';

$contentManager = new ContentManager();

// 1. We search for the category by the slug coming in the URL ($page)
$currentCategory = $contentManager->contentDao->getCategoryBySlug($page);

if (!$currentCategory) {
    echo "Categoría no encontrada.";
    return;
}

// 2. We fetch items using the compatible method
$items = $contentManager->getItemsByCategoryName($page);
?>

<div class="container-page">
    <div class="section-header">
        <h1><?php echo htmlspecialchars($currentCategory['nombre']); ?></h1>
    </div>

    <?php if (!empty($items)): ?>
        <div class="category-grid-layout">
            <?php foreach ($items as $item): ?>
                <?php renderCardItemRow($item); ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="content-placeholder">
            <p>No hay contenidos disponibles en esta sección.</p>
        </div>
    <?php endif; ?>
</div>