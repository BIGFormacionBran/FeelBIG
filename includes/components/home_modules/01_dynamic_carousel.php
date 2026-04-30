<?php
require_once __DIR__ . '/../../managers/main_manager.php';
require_once __DIR__ . '/../../utils/card_render_util.php';

$manager = new MainManager();
// Obtenemos los items de la categoría "Minijuegos"
$minijuegos = $manager->get_items_by_category_name('Minijuegos');

if (!empty($minijuegos)):
    $title = "Minijuegos Saludables";
    $viewAllLink = "minijuegos.php"; // Ajusta a tu ruta real
    $carouselID = 'carousel_minijuegos_' . uniqid(); 
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="home-module-wrapper carrusel-contenedor-global feelbig-swiper-section" id="<?php echo $carouselID; ?>" data-swiper-ready="false">
    <div class="module-header">
        <h2><?php echo $title; ?></h2>
        <?php if (isset($viewAllLink)): ?>
            <a href="<?php echo $viewAllLink; ?>" class="enlace-personalizado">Ver todos</a>
        <?php endif; ?>
    </div>

    <div class="contenedor-carrousel-eventos">
        <div class="fila-principal-carrousel">
            <div class="swiper-button-prev btn-nav-eventos">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
            </div>
            
            <div class="cuerpo-swiper">
                <div class="swiper swiper-feelbig-generic">
                    <div class="swiper-wrapper">
                        <?php foreach ($minijuegos as $item): ?>
                            <div class="swiper-slide">
                                <?php render_card_item_util($item); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="swiper-button-next btn-nav-eventos">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                    <path d="M9 18l6-6-6-6"></path>
                </svg>
            </div>
        </div>
        
        <div class="paginacion-externa">
            <div class="swiper-pagination-custom"></div>
        </div>
    </div>
</div>
<?php endif; ?>