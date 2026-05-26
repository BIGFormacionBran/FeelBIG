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

    echo "<div class='error-container'><h2>Contenido no disponible</h2><a href='/home'>Volver al inicio</a></div>";
}

function renderIndividualViewUtil($data) {
    $imageSource = $data['img'];
    $externalLink = $data['enlace_externo'] ?? null;
?>
    <article class="content-detail-article">
        <header class="detail-hero">
            <div class="hero-image-container">
                <img src="<?php echo $imageSource; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>" class="hero-main-img">
                <div class="hero-overlay"></div>
            </div>
            
            <div class="hero-content-wrapper">
                <?php if (!empty($data['badge'])): ?>
                    <span class="detail-category-tag"><?php echo htmlspecialchars($data['badge']); ?></span>
                <?php endif; ?>
                <h1 class="detail-main-title"><?php echo htmlspecialchars($data['name']); ?></h1>
                <?php if (!empty($data['date'])): ?>
                    <time class="detail-pub-date">Publicado el <?php echo date('d M, Y', strtotime($data['date'])); ?></time>
                <?php endif; ?>
            </div>
        </header>

        <div class="detail-body-container">
            <div class="detail-rich-text text-area">
                <?php echo $data['description']; ?>
            </div>

            <?php if (!empty($externalLink)): ?>
                <footer class="detail-footer-actions">
                    <div class="cta-separator"></div>
                    <a href="<?php echo $externalLink; ?>" target="_blank" rel="noopener" class="btn-primario btn-xl">
                        <span>ACCEDER AL RECURSO COMPLETO</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </footer>
            <?php endif; ?>
        </div>
    </article>
<?php
}