<?php

function render_individual_page($item = null) {
    if ($item !== null) {
        return "/" . ($item['type'] ?? 'default') . "/" . $item['name'];
    }

    global $routeParts;
    
    $itemType = $routeParts[0] ?? null;
    $itemName = isset($routeParts[1]) ? urldecode($routeParts[1]) : null;

    if ($itemType && $itemName) {
        $directory = __DIR__ . '/../components/home_modules/';
        $files = glob($directory . "*" . $itemType . ".php");

        if (!empty($files)) {
            ob_start();
            include $files[0];
            ob_end_clean();

            $foundItem = null;
            if (isset($items)) {
                foreach ($items as $item) {
                    if (trim($item['name']) === trim($itemName)) {
                        $foundItem = $item;
                        break;
                    }
                }
            }

            if ($foundItem) {
                render_individual_view_util($foundItem);
                return;
            }
        }
    }

    echo "<div class='error-container'><h2>Ítem no encontrado</h2><a href='/home'>Volver al inicio</a></div>";
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
            <img src="assets/img/<?php echo $data['img']; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>">
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