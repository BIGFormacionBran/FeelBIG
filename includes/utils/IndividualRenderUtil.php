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

    echo "<div class='error-container'><h2>Contenido no encontrado</h2><a href='/home'>Volver al inicio</a></div>";
}

function renderIndividualViewUtil($data) {
    $imageSource = $data['img'] ?? null;
    $videoSource = $data['video'] ?? null; 
    $externalLink = $data['enlace_externo'] ?? null;
?>
    <div class="view-detail-main">
        <div class="content-article-container">
            
            <div class="article-header">
                <?php if (!empty($data['badge'])): ?>
                    <div class="article-mini-badge"><?php echo htmlspecialchars($data['badge']); ?></div>
                <?php endif; ?>
                <div class="article-title"><?php echo htmlspecialchars($data['name']); ?></div>
                <?php if (!empty($data['date'])): ?>
                    <div class="article-meta">Publicado el <?php echo date('d/m/Y', strtotime($data['date'])); ?></div>
                <?php endif; ?>
            </div>

            <div class="article-media-container">
                <?php if (!empty($videoSource)): ?>
                    <div class="media-wrapper video-wrapper">
                        <?php if(strpos($videoSource, 'youtube.com') !== false || strpos($videoSource, 'youtu.be') !== false): ?>
                            <iframe src="<?php echo str_replace('watch?v=', 'embed/', $videoSource); ?>" frameborder="0" allowfullscreen></iframe>
                        <?php else: ?>
                            <video controls src="<?php echo $videoSource; ?>"></video>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($imageSource)): ?>
                    <div class="media-wrapper image-wrapper">
                        <img src="<?php echo $imageSource; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="article-content-body">
                <div class="text-area">
                    <?php echo $data['description']; ?>
                </div>

                <?php if (!empty($externalLink)): ?>
                    <div class="article-actions">
                        <a href="<?php echo $externalLink; ?>" target="_blank" rel="noopener" class="btn-primario btn-full-action">
                            <span>ACCEDER AL CONTENIDO</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:10px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
<?php
}