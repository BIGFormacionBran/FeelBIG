<?php
require_once __DIR__ . '/../utils/card_render_util.php';
$carouselID = 'carousel_' . uniqid();
?>
<div class="home-module-wrapper carrusel-contenedor-global" id="<?php echo $carouselID; ?>">
    <div class="module-header">
        <h2><?php echo $title; ?></h2>
        <?php if (isset($viewAllLink)): ?>
            <a href="<?php echo $viewAllLink; ?>" class="enlace-personalizado">Ver todos</a>
        <?php endif; ?>
    </div>

    <div class="carousel-main-viewport">
        <div class="carousel-nav-arrow prev" onclick="moveCarousel('<?php echo $carouselID; ?>', -1)">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M15 18l-6-6 6-6"></path></svg>
        </div>

        <div class="carousel-track">
            <?php foreach ($items as $item): ?>
                <div class="carousel-slide">
                    <?php render_card_item_util($item); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="carousel-nav-arrow next" onclick="moveCarousel('<?php echo $carouselID; ?>', 1)">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M9 18l6-6-6-6"></path></svg>
        </div>
    </div>

    <div class="carousel-dots">
        <?php foreach ($items as $i => $item): ?>
            <div class="dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="gotoSlide('<?php echo $carouselID; ?>', <?php echo $i; ?>)"></div>
        <?php endforeach; ?>
    </div>
</div>