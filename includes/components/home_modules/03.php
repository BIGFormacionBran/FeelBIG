<?php
require_once __DIR__ . '/../../managers/ContentManager.php';
require_once __DIR__ . '/../../utils/CardRenderUtil.php';

$contentManager = new ContentManager();
$carouselItems = $contentManager->getItemsByCategoryName('Minijuegos');

if (!empty($carouselItems)) {
    $carouselTitle = "Minijuegos Saludables";
    $exploreUrl = "minijuegos";
    
    // ACTIVAMOS EL USO DE SWIPER PARA ESTA PÁGINA
    $GLOBALS['needsSwiper'] = true;

    include __DIR__ . '/../generic/DynamicCarousel.php';
}
?>