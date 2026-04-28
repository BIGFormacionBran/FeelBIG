<?php
/**
 * Genera la URL para el carrusel o el HTML de la página vacía
 */
function render_individual_page($item = null) {
    // Si hay item, es una llamada desde el carrusel para obtener el enlace
    if ($item !== null) {
        return "index.php?page=item&type=" . ($item['type'] ?? 'default') . "&id=" . $item['id'];
    }

    // Si NO hay item, es el ruteador cargando la página individual vacía
    $itemId = $_GET['id'] ?? '---';
    $itemType = $_GET['type'] ?? '---';

    echo '<div class="container-page-individual">
            <div class="section-header-individual">
                <h1>Cargando item: ' . htmlspecialchars($itemId) . '</h1>
                <p class="subtitle">Categoría: ' . htmlspecialchars($itemType) . '</p>
            </div>
            <div class="content-placeholder-individual">
                </div>
          </div>';
}