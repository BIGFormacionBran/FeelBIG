<?php
require_once __DIR__ . '/../../managers/content_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$contentManager = new ContentManager();

// Configuramos los datos específicos para Minijuegos llamando al manager de contenido
$carouselTitle = "Minijuegos Saludables";
$exploreUrl = "minijuegos";
$carouselItems = $contentManager->get_items_by_category_name('Minijuegos');

// Cargamos las librerías necesarias una sola vez
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<?php
// Llamamos al carrusel genérico pasándole las variables
include __DIR__ . '/../generic/dynamic_carousel.php';
?>