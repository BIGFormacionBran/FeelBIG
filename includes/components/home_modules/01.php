<?php
require_once __DIR__ . '/../../managers/content_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$contentManager = new ContentManager();
$carouselTitle = "Minijuegos Saludables";
$exploreUrl = "minijuegos";
$carouselItems = $contentManager->get_items_by_category_name('Minijuegos');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<?php
include __DIR__ . '/../generic/dynamic_carousel.php';
?>