<?php
require_once __DIR__ . '/../managers/ContentManager.php';

function renderIndividualPage($item = null) {
    $contentManager = new ContentManager();

    if ($item !== null) {
        $category = $contentManager->getCategoryByItemId($item['id']);
        $categorySlug = $category ? strtolower(str_replace(' ', '-', $category['nombre'])) : 'contenido';
        return "/" . $categorySlug . "/" . str_replace(' ', '-', $item['name']);
    }

    global $routeParts;
    $itemNameFromUrl = isset($routeParts[1]) ? urldecode($routeParts[1]) : null;

    $invalidNames = ['img', 'assets', 'css', 'js', 'favicon.ico'];    
    if ($itemNameFromUrl && !in_array(strtolower($itemNameFromUrl), $invalidNames)) {
        $foundItem = $contentManager->getItemByName($itemNameFromUrl);
        if ($foundItem) {
            renderIndividualViewUtil($foundItem);
            return;
        }
    }

    echo "<div class='error-container'><h2>Ítem no encontrado</h2><a href='/home'>Volver al inicio</a></div>";
}

function renderIndividualViewUtil($data) {
    $imageSource = $data['img'];
    $externalLink = $data['enlace_externo'] ?? null;
?>
    <div class="detail-page-wrapper">
        <div class="detail-card">
            <div class="detail-image-section">
                <img src="<?php echo $imageSource; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>">
                <?php if (!empty($data['badge'])): ?>
                    <span class="detail-badge"><?php echo htmlspecialchars($data['badge']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="detail-info-section">
                <div class="detail-header">
                    <h1 class="detail-title"><?php echo htmlspecialchars($data['name']); ?></h1>
                    <?php if (!empty($data['date'])): ?>
                        <span class="detail-date"><?php echo date('d/m/Y', strtotime($data['date'])); ?></span>
                    <?php endif; ?>
                </div>

                <div class="detail-description text-area">
                    <?php echo $data['description']; ?>
                </div>

                <?php if (!empty($externalLink)): ?>
                    <div class="detail-actions">
                        <a href="<?php echo $externalLink; ?>" target="_blank" rel="noopener" class="btn-primario btn-cta">
                            ACCEDER AL CONTENIDO
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:8px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}