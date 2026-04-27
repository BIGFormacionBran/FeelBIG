<?php
require_once __DIR__ . '/../utils/card_render_util.php';
// ID único para que el JS no se líe si hay varios carruseles
$carouselID = 'carousel_' . uniqid();
?>
<div class="home-module-wrapper carrusel-contenedor-global" id="<?php echo $carouselID; ?>">
    <div class="module-header">
        <h2><?php echo $title; ?></h2>
        <?php if (isset($viewAllLink)): ?>
            <a href="<?php echo $viewAllLink; ?>" class="enlace-personalizado">Ver todos</a>
        <?php endif; ?>
    </div>

    <div class="carousel-main-viewport" style="position: relative; overflow: hidden; padding: 10px 0;">
        <div class="carousel-nav-arrow prev" onclick="moveCarousel('<?php echo $carouselID; ?>', -1)">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M15 18l-6-6 6-6"></path></svg>
        </div>

        <div class="content-carousel carousel-track" style="display: flex; transition: transform 0.5s ease; gap: 20px;">
            <?php foreach ($items as $item): ?>
                <div class="carousel-item carousel-slide" style="min-width: calc(33.333% - 14px); flex-shrink: 0;">
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