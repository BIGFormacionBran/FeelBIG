<?php
if (!empty($carouselItems)):
    $carouselId = 'fb_gen_cur_' . uniqid(); 
?>
<div class="home-module-wrapper feelbig-swiper-section" id="<?php echo $carouselId; ?>">
    <div class="module-header">
        <h2><?php echo htmlspecialchars($carouselTitle); ?></h2>
    </div>

    <div class="carousel-flex-layout">
        <div class="swiper-button-prev btn-nav-feelbig"></div>

        <div class="swiper swiper-feelbig-generic is-loading">
            <div class="swiper-wrapper">
                <?php foreach ($carouselItems as $item): ?>
                    <div class="swiper-slide">
                        <?php renderCardItemColumn($item); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="swiper-button-next btn-nav-feelbig"></div>
    </div>

    <div class="swiper-pagination-custom"></div>
</div>
<?php endif; ?>