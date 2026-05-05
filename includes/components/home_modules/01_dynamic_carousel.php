<?php
require_once __DIR__ . '/../../managers/main_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$manager = new MainManager();
$items = $manager->get_items_by_category_name('Minijuegos');

if (!empty($items)):
    $carouselID = 'fb_carousel_' . uniqid(); 
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="home-module-wrapper feelbig-swiper-section" id="<?php echo $carouselID; ?>">
    <div class="module-header">
        <h2>Minijuegos Saludables</h2>
        <a href="minijuegos" class="enlace-personalizado">Explorar todos los juegos</a>
    </div>

    <div class="carousel-flex-layout">
        <div class="swiper-button-prev btn-nav-feelbig"></div>

        <div class="swiper swiper-feelbig-generic">
            <div class="swiper-wrapper">
                <?php foreach ($items as $item): ?>
                    <div class="swiper-slide">
                        <?php render_card_item_util($item); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="swiper-button-next btn-nav-feelbig"></div>
    </div>

    <div class="swiper-pagination-custom"></div>
</div>
<?php endif; ?>