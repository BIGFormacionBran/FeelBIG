<?php
require_once __DIR__ . '/../utils/individual_render_util.php';

/**
 * Función principal que recibe el item desde el carrusel
 */
function render_individual_page($item) {
    $itemId = $item['id'];
    $itemType = $item['type'];
    
    echo '<div class="container-page-individual">
        <div class="section-header-individual">
            <h1>Cargando item: ' . htmlspecialchars($itemId) . '</h1>
            <p class="subtitle">Categoría: ' . htmlspecialchars($itemType) . '</p>
        </div>
        
        <div class="content-placeholder-individual">
        </div>
    </div>';
}