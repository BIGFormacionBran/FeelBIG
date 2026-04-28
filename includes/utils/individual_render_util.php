<?php
function render_individual_page($item = null) {
    // Si la función recibe un item (llamada desde el carrusel), devuelve el enlace
    if ($item !== null) {
        return "index.php?page=individual_view&type=" . ($item['type'] ?? 'default') . "&id=" . $item['id'];
    }

    // Si la función se llama sin parámetros (llamada desde individual_view.php), pinta la página
    $itemId = $_GET['id'] ?? 'N/A';
    $itemType = $_GET['type'] ?? 'N/A';
    ?>
    <div class="container-page">
        <div class="section-header">
            <h1>Cargando: <?php echo htmlspecialchars($itemId); ?></h1>
            <p class="subtitle">Tipo de contenido: <?php echo htmlspecialchars($itemType); ?></p>
        </div>
        <div class="content-placeholder">
            <p>Generando vista individual automática para el item...</p>
            </div>
    </div>
    <?php
}