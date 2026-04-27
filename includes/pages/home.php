<?php
require_once 'includes/utils/component_engine.php';
?>

<section class="hero-feelbig">
    <h1>Bienvenido a Feel BiG</h1>
    <p>Soluciones de bienestar para empresas y familias.</p>
</section>

<div class="home-dynamic-container">
    <?php 
        // Renderiza automáticamente todo lo que esté en esta carpeta
        renderAutoComponents('includes/components/home_modules'); 
    ?>
</div>