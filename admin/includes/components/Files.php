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

        <div style="flex:1; overflow:hidden; display:flex; flex-direction:column;">
            <div id="fm-tab-browse" class="fm-tab-content">
                <div class="file-grid" id="file-grid">
                    <?php if (empty($allFiles)): ?>
                        <p class="text-muted" style="grid-column: 1/-1;">No hay archivos en el servidor.</p>
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
                                    <div style="position:relative; width:100%; height:100%; background:#000; display:flex; align-items:center; justify-content:center;">
                                        <img src="/assets/admin/img/video-placeholder.png" style="width:100%; height:100%; object-fit:cover; opacity:0.8;">
                                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:white; font-size:24px; text-shadow:0 0 10px rgba(0,0,0,0.5);">▶</div>
                                        <span style="position:absolute; top:5px; right:5px; background:var(--color-principal-admin); color:white; font-size:10px; padding:2px 6px; border-radius:4px;">VIDEO</span>
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
                    <div style="text-align:center;">
                        <p style="font-size:1.1rem; margin:0 0 5px 0; color:#444;">Arrastra y suelta tus archivos aquí</p>
                        <span style="display:block; color:#999; margin-bottom:15px;">— o —</span>
                        <button type="button" class="btn-primario" onclick="document.getElementById('fm-upload-input').click()" style="max-width:250px;">Examinar Equipo</button>
                        <p class="text-muted" style="margin-top:15px;" id="upload-instruction">Formatos: JPG, PNG, MP4, WebM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
