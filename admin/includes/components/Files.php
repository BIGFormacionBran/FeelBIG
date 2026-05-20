<?php
if (!isset($admin) || $admin === null) {
    require_once __DIR__ . '/../managers/AdminManager.php';
    $manager = new AdminManager();
} else {
    $manager = $admin;
}

$images = $manager->media->listPhysicalFiles('images');
$videos = $manager->media->listPhysicalFiles('videos');
$allFiles = array_merge($images, $videos);
?>

<div id="file-manager-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Gestor de Archivos</h3>
            <button type="button" class="btn-close-modal" id="fm-close-x">&times;</button>
        </div>

        <div class="fm-tabs">
            <button type="button" class="fm-tab-btn active" data-tab="fm-tab-browse">Explorar</button>
            <button type="button" class="fm-tab-btn" data-tab="fm-tab-upload">Subir archivo</button>
        </div>
        
        <div class="modal-body">
            <div id="fm-tab-browse" class="fm-tab-content">
                <div class="file-grid" id="file-grid">
                    <?php if (empty($allFiles)): ?>
                        <p class="text-muted">No hay archivos en el servidor.</p>
                    <?php endif; ?>
                    
                    <?php foreach ($allFiles as $file): 
                        $type = (strpos($file['path'], 'videos/') !== false) ? 'videos' : 'images';
                    ?>
                        <div class="file-item" data-path="<?php echo $file['path']; ?>" data-type="<?php echo $type; ?>">
                            <div class="file-preview">
                                <?php if ($type === 'images'): ?>
                                    <img src="/<?php echo $file['url']; ?>" alt="Preview">
                                <?php else: ?>
                                    <div class="video-placeholder">VIDEO</div>
                                <?php endif; ?>
                                <button type="button" class="btn-fm-delete" data-path="<?php echo $file['path']; ?>">&times;</button>
                            </div>
                            <span class="file-name"><?php echo $file['name']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="fm-tab-upload" class="fm-tab-content hidden">
                <div class="upload-zone" id="fm-drop-zone">
                    <label class="admin-label" for="fm-upload-input">Seleccionar archivo:</label>
                    <input type="file" id="fm-upload-input" style="display:none;" accept="image/*,video/*">
                    <button type="button" class="btn-primario" onclick="document.getElementById('fm-upload-input').click()">Examinar</button>
                </div>
            </div>
        </div>
    </div>
</div>