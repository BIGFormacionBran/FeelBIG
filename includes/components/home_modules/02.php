<?php
require_once __DIR__ . '/../../managers/ContentManager.php';
require_once __DIR__ . '/../../utils/CardRenderUtil.php';

$contentManager = new ContentManager();

// 1. Obtenemos las categorías raíz de la base de datos (id_padre IS NULL)
$categoriaRaices = $contentManager->getHomeStructure();

// 2. Mapeamos los resultados al formato exacto que consume renderCardItemColumn
$pilares = array_map(function($cat) {
    return [
        'id'          => $cat['id'],
        'name'        => $cat['nombre'],
        'type'        => 'category',
        'img'         => !empty($cat['imagen']) ? $cat['imagen'] : 'assets/img/default_category.png', 
        'badge'       => 'Pilar',
        'slug'        => str_replace(' ', '-', strtolower($cat['nombre'])),
        'description' => '' // Opcional: añade un campo descripción a tu tabla si deseas mostrarlo en el futuro
    ];
}, $categoriaRaices);
?>

<div class="home-module-wrapper">
    <div class="module-header">
        <div class="article-title" style="font-size: 28px;">Nuestros Pilares de Salud</div>
    </div>

    <?php if (!empty($pilares)): ?>
        <div class="category-grid-layout" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
            <?php foreach ($pilares as $pilar): ?>
                <div class="grid-item-wrapper">
                    <?php renderCardItemColumn($pilar); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="content-placeholder">
            <p>No se han encontrado pilares configurados en el sistema.</p>
        </div>
    <?php endif; ?>
</div>