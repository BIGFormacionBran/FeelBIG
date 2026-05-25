<?php
$manager = $admin ?? new AdminManager();
$images = $manager->media->listPhysicalFiles('images');
$videos = $manager->media->listPhysicalFiles('videos');
$allFiles = array_merge($images, $videos);
?>

<div id="file-manager-modal" class="hidden">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Gestor de Archivos</h3>
            <button type="button" id="fm-close-x">&times;</button>
        </div>

        <div class="fm-tabs">
            <button type="button" class="fm-tab-btn active" data-tab="fm-tab-browse">Explorar</button>
            <button type="button" class="fm-tab-btn" data-tab="fm-tab-upload">Subir archivo</button>
        </div>

        <div class="fm-body-container">
            <div id="fm-tab-browse" class="fm-tab-content">
                <div class="file-grid" id="file-grid">
                    <?php if (empty($allFiles)): ?>
                        <p class="text-muted empty-msg">No hay archivos en el servidor.</p>
                    <?php endif; ?>

                    <?php foreach ($allFiles as $file):
                        $isVid = (strpos($file['path'], 'videos/') !== false);
                        $type = $isVid ? 'videos' : 'images';
                    ?>
                        <div class="file-item" data-path="<?php echo htmlspecialchars($file['path']); ?>" data-type="<?php echo $type; ?>">
                            <div class="file-preview">
                                <?php if (!$isVid): ?>
                                    <img src="/<?php echo htmlspecialchars($file['path']); ?>" alt="Preview">
                                <?php else: ?>
                                    <div class="video-item-wrapper">
                                        <img src="/assets/admin/img/video-placeholder.png" class="video-placeholder-img">
                                        <div class="video-play-icon">▶</div>
                                        <span class="video-badge">VIDEO</span>
                                    </div>
                                <?php endif; ?>
                                <button type="button" class="btn-fm-delete" data-path="<?php echo htmlspecialchars($file['path']); ?>" title="Eliminar">&times;</button>
                            </div>
                            <span class="file-name"><?php echo htmlspecialchars($file['name']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="fm-tab-upload" class="fm-tab-content hidden">
                <div class="fm-drop-zone" id="fm-drop-zone">
                    <input type="file" id="fm-upload-input">
                    <div class="fm-upload-content">
                        <p class="fm-upload-title">Arrastra y suelta tus archivos aquí</p>
                        <span class="fm-upload-or">— o —</span>
                        <button type="button" class="btn-primario">Examinar Equipo</button>
                        <p class="text-muted" id="upload-instruction">Formatos: JPG, PNG, MP4, WebM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>