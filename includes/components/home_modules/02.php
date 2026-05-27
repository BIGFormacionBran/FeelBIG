<?php
require_once __DIR__ . '/../../managers/ContentManager.php';
require_once __DIR__ . '/../../utils/CardRenderUtil.php';

$contentManager = new ContentManager();

// 1. Obtenemos todas las categorías raíz
$categoriaRaices = $contentManager->getHomeStructure();

// 2. Si hay datos, mezclamos el array aleatoriamente y tomamos máximo 4
if (!empty($categoriaRaices)) {
    shuffle($categoriaRaices);
    $categoriaRaices = array_slice($categoriaRaices, 0, 4);
}

// 3. Mapeamos los resultados al formato que consume renderCardItemColumn
$pilares = array_map(function($cat) {
    return [
        'id'          => $cat['id'],
        'name'        => $cat['nombre'],
        'type'        => 'category',
        'img'         => !empty($cat['imagen']) ? $cat['imagen'] : 'assets/img/default_category.png', 
        'badge'       => 'Destacado',
        'slug'        => str_replace(' ', '-', strtolower($cat['nombre'])),
        'description' => '' 
    ];
}, $categoriaRaices);
?>

<div class="home-module-wrapper">
    <div class="module-header">
        <div class="article-title" style="font-size: 28px;">Descubre Feel BiG</div>
    </div>

    <?php if (!empty($pilares)): ?>
        <div class="category-grid-layout" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
            <?php foreach ($pilares as $pilar): ?>
                <div class="grid-item-wrapper">
                    <?php renderCardItemColumn($pilar); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="content-placeholder">
            <p>No hay categorías disponibles para mostrar en este momento.</p>
        </div>
    <?php endif; ?>
</div>