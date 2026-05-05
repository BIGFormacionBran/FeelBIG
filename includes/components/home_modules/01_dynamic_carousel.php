<?php
require_once __DIR__ . '/../../managers/main_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$manager = new MainManager();
$items = $manager->get_items_by_category_name('Minijuegos');

if (!empty($items)):
    $title = "Minijuegos Saludables";
    $viewAllLink = "minijuegos";
    $carouselID = 'fb_carousel_' . uniqid(); 
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="home-module-wrapper feelbig-swiper-section" id="<?php echo $carouselID; ?>">
    <div class="module-header">
        <h2><?php echo $title; ?></h2>
        <?php if (isset($viewAllLink)): ?>
            <a href="<?php echo $viewAllLink; ?>" class="enlace-personalizado">Ver todos</a>
        <?php endif; ?>
    </div>

    <div class="feelbig-carousel-parent">
        
        <div class="swiper-button-prev btn-nav-feelbig">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
        </div>

        <div class="swiper swiper-feelbig-generic">
            <div class="swiper-wrapper">
                <?php foreach ($items as $item): ?>
                    <div class="swiper-slide">
                        <?php render_card_item_util($item); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="swiper-button-next btn-nav-feelbig">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"></path>
            </svg>
        </div>

    </div>

    <div class="pagination-feelbig">
        <div class="swiper-pagination-custom"></div>
    </div>
</div>
<?php endif; ?>