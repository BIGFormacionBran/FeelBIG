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
            <h3>Explorador de Archivos (<?php echo ucfirst($type); ?>)</h3>
            <div class="header-actions">
                <input type="file" id="fm-upload-input" class="hidden" accept="<?php echo $type === 'images' ? 'image/*' : 'video/*'; ?>">
                <button type="button" class="btn-primario" onclick="document.getElementById('fm-upload-input').click()">+ Subir</button>
                <button type="button" class="btn-close-modal">&times;</button>
            </div>
        </div>
        
        <div class="modal-body">
            <div class="file-grid" id="file-grid">
                <?php foreach ($files as $file): ?>
                    <div class="file-item" data-path="<?php echo $file['path']; ?>">
                        <div class="file-preview">
                            <?php if ($type === 'images'): ?>
                                <img src="<?php echo $file['url']; ?>" alt="Preview">
                            <?php else: ?>
                                <div class="video-icon">🎬</div>
                            <?php endif; ?>
                            <button class="btn-fm-delete" onclick="fileManager.deleteFile('<?php echo $file['path']; ?>', this)">×</button>
                        </div>
                        <span class="file-name"><?php echo $file['name']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>