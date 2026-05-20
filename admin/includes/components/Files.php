<?php
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
            <h3>Gestor de Archivos (<?php echo strtoupper($type); ?>)</h3>
            <button type="button" class="btn-close-modal" id="fm-close-x">&times;</button>
        </div>

        <div class="fm-tabs">
            <button type="button" class="fm-tab-btn active" data-tab="fm-tab-browse">Explorar</button>
            <button type="button" class="fm-tab-btn" data-tab="fm-tab-upload">Subir archivo</button>
        </div>
        
        <div class="modal-body">
            <div id="fm-tab-browse" class="fm-tab-content">
                <div class="file-grid" id="file-grid">
                    <?php if (empty($files)): ?>
                        <p class="text-muted">No hay archivos disponibles.</p>
                    <?php endif; ?>
                    <?php foreach ($files as $file): ?>
                        <div class="file-item" data-path="<?php echo $file['path']; ?>">
                            <div class="file-preview">
                                <?php if ($type === 'images'): ?>
                                    <img src="/<?php echo $file['url']; ?>" alt="Vista previa">
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
                    <p>Seleccionar archivo para subir</p>
                    <input type="file" id="fm-upload-input" class="hidden" accept="<?php echo $type === 'images' ? 'image/*' : 'video/*'; ?>">
                    <button type="button" class="btn-primario" onclick="document.getElementById('fm-upload-input').click()">Examinar</button>
                </div>
            </div>
        </div>
    </div>
</div>