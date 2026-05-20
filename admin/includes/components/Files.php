<?php
// CORRECCIÓN: Aseguramos que el manager esté disponible sin importar el scope de la inclusión
if (!isset($admin) || $admin === null) {
    require_once __DIR__ . '/../managers/AdminManager.php';
    $manager = new AdminManager();
} else {
    $manager = $admin;
}

$type = $_GET['file_type'] ?? 'images';
$files = $manager->media->listPhysicalFiles($type);
?>

<div id="file-manager-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Explorador de Archivos (<?php echo ucfirst($type); ?>)</h3>
            <button type="button" class="btn-close-modal" id="btn-close-modal">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="file-grid" id="file-grid">
                <?php if (empty($files)): ?>
                    <p class="empty-msg">No hay archivos en esta categoría.</p>
                <?php endif; ?>

                <?php foreach ($files as $file): ?>
                    <div class="file-item" 
                         data-path="<?php echo $file['path']; ?>" 
                         title="<?php echo $file['name']; ?>">
                        <?php if ($type === 'images'): ?>
                            <img src="<?php echo $file['url']; ?>" alt="Preview">
                        <?php else: ?>
                            <div class="video-icon">🎬</div>
                        <?php endif; ?>
                        <span class="file-name"><?php echo $file['name']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>