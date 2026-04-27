<?php
// Aseguramos que las utilidades de renderizado estén disponibles
require_once 'includes/utils/render_util.php';
?>

<section class="hero-feelbig">
    <h1>Bienvenido a Feel BiG</h1>
    <p>Soluciones de bienestar para empresas y familias.</p>
</section>

<div class="home-dynamic-container">
    <?php 
        // Corregido: Nombre de función sincronizado con render_util.php
        render_auto_components_util('includes/components/home_modules'); 
    ?>
</div>