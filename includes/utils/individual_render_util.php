<?php

function render_individual_page($item = null) {
    // Si la función recibe un item (llamada desde el carrusel), devuelve el enlace
    if ($item !== null) {
        return "index.php?page=individual_view&type=" . ($item['type'] ?? 'default') . "&id=" . $item['id'];
    }

    // Lógica para mostrar la página individual
    $itemId = $_GET['id'] ?? null;
    $itemType = $_GET['type'] ?? null;

    if ($itemId && $itemType) {
        // 1. Buscamos el archivo que contiene el tipo (ej: 01_minijuegos.php)
        $directory = __DIR__ . '/../components/home_modules/';
        $files = glob($directory . "*" . $itemType . ".php");

        if (!empty($files)) {
            // 2. Cargamos el archivo para tener el array $items (bloqueamos el include para que no pinte el carrusel)
            ob_start();
            include $files[0];
            ob_end_clean();

            // 3. Buscamos el ítem específico por ID
            $foundItem = null;
            foreach ($items as $item) {
                if ($item['id'] == $itemId) {
                    $foundItem = $item;
                    break;
                }
            }

            if ($foundItem) {
                // 4. Mandamos el item al util de renderizado final
                render_individual_view_util($foundItem);
                return;
            }
        }
    }

    echo "<p>Ítem no encontrado.</p>";
}

function render_individual_view_util($data) {
    ?>
    <div class="creiss-single-wrapper">
        <div class="creiss-single-header">
            <h1 class="creiss-title"><?php echo htmlspecialchars($data['name']); ?></h1>
        </div>

        <?php if (!empty($data['extra_info'])): ?>
        <div class="creiss-meta-flex">
            <?php foreach ($data['extra_info'] as $label => $value): ?>
                <div class="meta-item-box">
                    <strong class="meta-label"><?php echo htmlspecialchars($label); ?></strong>
                    <span class="meta-value"><?php echo htmlspecialchars($value); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="creiss-featured-image">
            <img src="assets/img/<?php echo $item['img'] ?? $data['img']; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>">
            <?php if (isset($data['badge'])): ?>
                <span class="badge-float"><?php echo htmlspecialchars($data['badge']); ?></span>
            <?php endif; ?>
        </div>

        <div class="creiss-body-content">
            <div class="creiss-custom-block">
                <div class="text-area">
                    <?php echo $data['description']; ?>
                </div>
            </div>
            
            <div class="creiss-footer-actions">
                <a href="javascript:history.back()" class="btn-primario btn-back">VOLVER</a>
            </div>
        </div>
    </div>
    <?php
}