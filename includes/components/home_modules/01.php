<?php
require_once __DIR__ . '/../../managers/content_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$contentManager = new ContentManager();
$carouselItems = $contentManager->get_items_by_category_name('Minijuegos');

if (!empty($carouselItems)) {
    $carouselTitle = "Minijuegos Saludables";
    $exploreUrl = "minijuegos";
    
    // ACTIVAMOS EL USO DE SWIPER PARA ESTA PÁGINA
    $GLOBALS['needs_swiper'] = true;

    include __DIR__ . '/../generic/dynamic_carousel.php';
}
?>