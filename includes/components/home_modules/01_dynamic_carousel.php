<?php
require_once __DIR__ . '/../../managers/main_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$manager = new MainManager();
// Los datos vienen del manager, el carrusel solo los recibe y renderiza
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

    <div class="feelbig-carousel-container">
        <div class="feelbig-carousel-row">
            
            <div class="swiper-button-prev btn-nav-feelbig"></div>
            
            <div class="swiper swiper-feelbig-generic">
                <div class="swiper-wrapper">
                    <?php foreach ($items as $item): ?>
                        <div class="swiper-slide">
                            <?php 
                            // El carrusel es genérico: no conoce los datos, delega en la util
                            render_card_item_util($item); 
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="swiper-button-next btn-nav-feelbig"></div>
        </div>
        
        <div class="pagination-feelbig">
            <div class="swiper-pagination-custom"></div>
        </div>
    </div>
</div>
<?php endif; ?>