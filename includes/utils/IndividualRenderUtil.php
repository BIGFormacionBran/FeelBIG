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
?>
    <div class="creiss-single-wrapper">
        <h1 class="creiss-title"><?php echo htmlspecialchars($data['name']); ?></h1>
        <div class="creiss-featured-image">
            <img src="<?php echo $imageSource; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>">
        </div>
        <div class="creiss-body-content">
            <div class="text-area"><?php echo $data['description']; ?></div>
            <a href="javascript:history.back()" class="btn-primario">VOLVER</a>
        </div>
    </div>
<?php
}