<?php
/** @var AdminManager $admin */
// El orquestador ya está disponible desde la página que incluye este componente
$type = $_GET['file_type'] ?? 'images';
$files = $admin->media->listPhysicalFiles($type);
?>

<div id="file-manager-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Explorador de Archivos (<?php echo ucfirst($type); ?>)</h3>
            <button type="button" class="btn-close-modal">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="file-grid">
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