<?php
require_once __DIR__ . '/../../managers/content_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';
require_once __DIR__ . '/../../utils/logger_util.php';

Logger::info("Module_01: Cargando módulo de Minijuegos.");

$contentManager = new ContentManager();
$carouselTitle = "Minijuegos Saludables";
$exploreUrl = "minijuegos";
$carouselItems = $contentManager->get_items_by_category_name('Minijuegos');

if (empty($carouselItems)) {
    Logger::error("Module_01: No se encontraron items para 'Minijuegos'.");
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<?php
include __DIR__ . '/../generic/dynamic_carousel.php';
?>