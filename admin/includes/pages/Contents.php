<?php
require_once __DIR__ . '/../managers/AdminContentManager.php';
$adminManager = new AdminContentManager();

$status = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'add') {
        if ($adminManager->createContent($_POST)) $status = "success";
        else $status = "error";
    }
    elseif ($action === 'edit') {
        if ($adminManager->updateContent($_POST['id'], $_POST)) $status = "success";
        else $status = "error";
    }
    elseif ($action === 'delete') {
        if ($adminManager->deleteContent($_POST['id'])) $status = "success";
        else $status = "error";
    }
}
$contenidos = $adminManager->listAllContents();
$categorias = $adminManager->listAllCategoriesOrdered();
?>

<div class="admin-page-container">
    <div class="admin-header-section">
        <h2>Gestión de Contenidos</h2>
    </div>

    <?php if ($status === "success"): ?>
        <div class="admin-status-alert success">✅ Operación realizada con éxito.</div>
    <?php elseif ($status === "error"): ?>
        <div class="admin-status-alert error">❌ Error en la operación.</div>
    <?php endif; ?>

    <div class="admin-flex-layout">
        <div class="admin-card side-form" data-entity="Contenido">
            <div class="admin-card-title" id="form-title">Nuevo Contenido</div>
            <form method="POST" id="content-form">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="con-id" value="">
                
                <div class="admin-form-group">
                    <label class="admin-label">Título:</label>
                    <input type="text" name="nombre" id="con-nombre" required class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Categoría:</label>
                    <select name="id_categoria" id="con-id_categoria" required class="admin-select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Clasificación:</label>
                    <input type="text" name="clasificacion" id="con-clasificacion" class="admin-input" placeholder="Ej: Formación">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">URL Imagen:</label>
                    <input type="text" name="imagen" id="con-imagen" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Video (Archivo):</label>
                    <input type="text" name="video" id="con-video" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Descripción Breve:</label>
                    <textarea name="descripcion_breve" id="con-descripcion_breve" class="admin-input admin-textarea-small"></textarea>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Enlace Externo:</label>
                    <input type="text" name="enlace_externo" id="con-enlace_externo" class="admin-input">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-submit">Guardar Contenido</button>
                    <button type="button" class="btn-secundario hidden" id="btn-cancel" onclick="resetForm(this)">Cancelar Edición</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Listado de Contenidos</div>
            
            <div class="admin-list-header">
                <div class="col-id">ID</div>
                <div class="col-name">Título y Descripción</div>
                <div class="col-info">Info / Archivos</div>
                <div class="col-level">Categoría</div>
                <div class="col-actions">Acciones</div>
            </div>

            <?php foreach($contenidos as $con): ?>
                <div class="admin-list-row">
                    <div class="col-id"><?php echo $con['id']; ?></div>
                    
                    <div class="col-name">
                        <span class="bold"><?php echo htmlspecialchars($con['nombre']); ?></span>
                        <span class="small-text"><?php echo htmlspecialchars(mb_strimwidth($con['descripcion_breve'], 0, 60, "...")); ?></span>
                    </div>

                    <div class="col-info">
                        <div><strong>Tipo:</strong> <?php echo htmlspecialchars($con['clasificacion'] ?: '-'); ?></div>
                        <div class="small-text">
                            <?php if($con['video']): ?> 🎥 <?php echo htmlspecialchars($con['video']); endif; ?>
                            <?php if($con['imagen']): ?> 🖼️ Imagen lista<?php endif; ?>
                        </div>
                    </div>

                    <div class="col-level">
                        <span class="admin-badge badge-category">
                            <?php echo htmlspecialchars($con['categoria_nombre']); ?>
                        </span>
                    </div>

                    <div class="col-actions">
                        <button class="action-edit" onclick='prepareEdit(<?php echo json_encode($con); ?>)'>Editar</button>
                        <form method="POST" class="inline" onsubmit="return confirm('¿Borrar este contenido?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $con['id']; ?>">
                            <button type="submit" class="action-delete-clean">Borrar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>