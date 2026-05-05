<?php
require_once __DIR__ . '/../managers/content_manager.php';

function render_individual_page($item = null) {
    $contentManager = new ContentManager();

    if ($item !== null) {
        $categoria = $contentManager->get_category_by_item_id($item['id']);
        $catSlug = $categoria ? strtolower(str_replace(' ', '-', $categoria['nombre'])) : 'contenido';
        return "/" . $catSlug . "/" . str_replace(' ', '-', $item['name']);
    }

    global $routeParts;
    $itemNameFromUrl = isset($routeParts[1]) ? urldecode($routeParts[1]) : null;

    if ($itemNameFromUrl) {
        $foundItem = $contentManager->get_item_by_name($itemNameFromUrl);
        if ($foundItem) {
            render_individual_view_util($foundItem);
            return;
        }
    }

    echo "<div class='error-container'><h2>Ítem no encontrado</h2><a href='/home'>Volver al inicio</a></div>";
}

function render_individual_view_util($data) {
    // Si la imagen de la DB no empieza por http, asumimos que es local en assets/img/
    $imgSrc = (strpos($data['img'], 'http') === 0) ? $data['img'] : '/assets/img/' . $data['img'];
?>
    <div class="creiss-single-wrapper">
        <h1 class="creiss-title"><?php echo htmlspecialchars($data['name']); ?></h1>
        <div class="creiss-featured-image">
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($data['name']); ?>">
        </div>
        <div class="creiss-body-content">
            <div class="text-area"><?php echo $data['description']; ?></div>
            <a href="javascript:history.back()" class="btn-primario">VOLVER</a>
        </div>
    </div>
<?php
}