<?php
require_once __DIR__ . '/../utils/individual_render_util.php';

/**
 * Función que genera la URL y prepara la estructura de la página
 */
function render_individual_page($item) {
    // 1. Si la función se llama desde el carrusel, devolvemos la URL
    if (isset($item['id'])) {
        return "index.php?page=item&type=" . ($item['type'] ?? 'default') . "&id=" . $item['id'];
    }

    // 2. Lógica para cuando la página ya se ha cargado (URL detectada)
    $itemId = $_GET['id'] ?? 'Desconocido';
    $itemType = $_GET['type'] ?? 'General';

    echo '<div class="container-page-individual">
        <div class="section-header-individual">
            <h1>Cargando item: ' . htmlspecialchars($itemId) . '</h1>
            <p class="subtitle">Categoría: ' . htmlspecialchars($itemType) . '</p>
        </div>
        <div class="content-placeholder-individual">
            </div>
    </div>';
}

// Si entramos en esta página por URL (page=item), ejecutamos la creación de la página vacía
if (isset($_GET['page']) && $_GET['page'] === 'item') {
    render_individual_page(null);
}