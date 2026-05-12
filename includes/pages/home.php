<?php
// Aseguramos que las utilidades de renderizado estén disponibles
require_once 'includes/utils/render_util.php';
?>

<div class="home-dynamic-container">
    <?php 
        render_auto_components_util('includes/components/home_modules'); 
    ?>
</div>