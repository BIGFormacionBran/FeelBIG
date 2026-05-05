<?php
/**
 * @var string $carouselTitle Título de la sección
 * @var string $exploreUrl Link de "Ver todos"
 * @var array $carouselItems Lista de items formateados para card
 */
if (!empty($carouselItems)):
    $carouselID = 'fb_gen_cur_' . uniqid(); 
?>
<div class="home-module-wrapper feelbig-swiper-section" id="<?php echo $carouselID; ?>">
    <div class="module-header">
        <h2><?php echo htmlspecialchars($carouselTitle); ?></h2>
        <?php if($exploreUrl): ?>
            <a href="<?php echo $exploreUrl; ?>" class="enlace-personalizado">Explorar todo</a>
        <?php endif; ?>
    </div>

    <div class="carousel-flex-layout">
        <div class="swiper-button-prev btn-nav-feelbig"></div>

        <div class="swiper swiper-feelbig-generic">
            <div class="swiper-wrapper">
                <?php foreach ($carouselItems as $item): ?>
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