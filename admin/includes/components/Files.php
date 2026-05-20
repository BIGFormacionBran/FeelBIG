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
                                    <div class="video-preview">
                                        <img src="/assets/admin/img/video-placeholder.png" class="video-thumb-frame" alt="Video">
                                        <div class="video-overlay-icon">▶</div>
                                        <span class="badge-video">VIDEO</span>
                                    </div>
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
                    <input type="file" id="fm-upload-input" style="opacity:0; position:absolute; z-index:-1;" accept="image/*,video/*">
                    
                    <div class="drop-zone-instruction">
                        <p>Arrastra y suelta tus archivos aquí</p>
                        <span>— o —</span>
                    </div>

                    <button type="button" class="btn-primario" onclick="document.getElementById('fm-upload-input').click()">Examinar</button>
                    <p class="text-muted" style="margin-top:10px;" id="upload-instruction">Haz clic en Examinar o arrastra un archivo</p>
                </div>
            </div>
        </div>
    </div>
</div>