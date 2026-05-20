<?php
require_once __DIR__ . '/../managers/AdminManager.php';
$admin = new AdminManager();

$status = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $result = $admin->handleRequest($_POST);
    if ($result) {
        $status = "success";
    } else {
        $status = "error";
        $message = "Error al procesar la solicitud en el servidor.";
    }
}

$contenidos = $admin->contents->listAllContents();
$categorias = $admin->contents->listAllCategoriesOrdered();
?>

<div class="admin-page-container">
    <div class="admin-header-section">
        <h2>Gestión de Contenidos</h2>
    </div>

    <?php include __DIR__ . '/../components/Alerts.php'; ?>

    <div class="admin-flex-layout">
        <div class="admin-card side-form" data-entity="Contenido">
            <div class="admin-card-title" id="form-title">Nuevo Contenido</div>
            
            <form method="POST" id="content-form">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="con-id" value="">
                
                <div class="admin-form-group">
                    <label class="admin-label" for="con-nombre">Título:</label>
                    <input type="text" name="nombre" id="con-nombre" required class="admin-input" placeholder="Nombre del contenido">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label" for="con-id_categoria">Categoría:</label>
                    <select name="id_categoria" id="con-id_categoria" required class="admin-select">
                        <option value="">-- Seleccionar Categoría --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo ($c['id_padre'] ? '— ' : '') . htmlspecialchars($c['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label" for="con-imagen">Imagen de Portada:</label>
                    <div class="media-selector-wrapper">
                        <input type="hidden" name="imagen" id="con-imagen">
                        <div id="con-imagen-preview" class="media-preview-box">
                            <span class="text-muted">Sin archivo</span>
                        </div>
                        <button type="button" class="btn-open-filemanager btn-primario" data-target="con-imagen">Seleccionar Imagen</button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label" for="con-video">Archivo de Video:</label>
                    <div class="media-selector-wrapper">
                        <input type="hidden" name="video" id="con-video">
                        <div id="con-video-preview" class="media-preview-box">
                            <span class="text-muted">Sin archivo</span>
                        </div>
                        <button type="button" class="btn-open-filemanager btn-primario" data-target="con-video">Seleccionar Video</button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label" for="con-clasificacion">Clasificación:</label>
                    <input type="text" name="clasificacion" id="con-clasificacion" class="admin-input" placeholder="Ej: Formación, Tutorial...">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label" for="con-descripcion_breve">Descripción:</label>
                    <textarea name="descripcion_breve" id="con-descripcion_breve" class="admin-input admin-textarea-small" placeholder="Breve resumen..."></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" style="margin-top:0;">Guardar</button>
                    <button type="button" id="btn-cancel" class="action-delete-clean hidden" onclick="resetForm(this)">Cancelar</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Listado</div>
            <div class="admin-list-header">
                <div class="col-id">ID</div>
                <div class="col-name">Título</div>
                <div class="col-level">Categoría</div>
                <div class="col-actions">Acciones</div>
            </div>

            <?php foreach($contenidos as $con): ?>
                <div class="admin-list-row">
                    <div class="col-id"><?php echo $con['id']; ?></div>
                    <div class="col-name"><strong><?php echo htmlspecialchars($con['nombre']); ?></strong></div>
                    <div class="col-level"><span class="admin-badge badge-category"><?php echo htmlspecialchars($con['categoria_nombre']); ?></span></div>
                    <div class="col-actions">
                        <button class="action-edit" onclick='prepareEdit(<?php echo json_encode($con); ?>)'>Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
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
<?php $admin->renderFileManager(); ?>