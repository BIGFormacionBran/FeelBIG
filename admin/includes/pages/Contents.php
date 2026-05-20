<?php
// Usamos el AdminManager como orquestador único
require_once __DIR__ . '/../managers/AdminManager.php';
$admin = new AdminManager();

$status = "";
$message = "";

// Procesamiento de peticiones a través del Orquestador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $result = $admin->handleRequest($_POST);
    if ($result) {
        $status = "success";
    } else {
        $status = "error";
        $message = "Error al procesar la solicitud en el servidor.";
    }
}

// Obtenemos datos a través de los sub-managers del orquestador
$contenidos = $admin->contents->listAllContents();
$categorias = $admin->contents->listAllCategoriesOrdered();
?>

<div class="admin-page-container">
    <div class="admin-header-section">
        <h2>Gestión de Contenidos</h2>
    </div>

    <?php if ($status === "success"): ?>
        <div class="admin-status-alert success">✅ Operación realizada con éxito.</div>
    <?php elseif ($status === "error"): ?>
        <div class="admin-status-alert error">❌ <?php echo $message ?: "Error en la operación."; ?></div>
    <?php endif; ?>

    <div class="admin-flex-layout">
        <div class="admin-card side-form" data-entity="Contenido">
            <div class="admin-card-title" id="form-title">Nuevo Contenido</div>
            <form method="POST" id="content-form">
                <input type="hidden" name="action" id="con-action" value="add">
                <input type="hidden" name="id" id="con-id" value="">
                
                <div class="admin-form-group">
                    <label class="admin-label">Título:</label>
                    <input type="text" name="nombre" id="con-nombre" required class="admin-input" placeholder="Nombre del contenido">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Categoría:</label>
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
                    <label class="admin-label">Imagen de Portada:</label>
                    <div class="admin-file-picker">
                        <input type="text" name="imagen" id="con-imagen" readonly placeholder="Ningún archivo seleccionado" class="admin-input">
                        <button type="button" class="btn-open-filemanager" data-target="con-imagen">Seleccionar</button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Archivo de Video:</label>
                    <div class="admin-file-picker">
                        <input type="text" name="video" id="con-video" readonly placeholder="Ningún archivo seleccionado" class="admin-input">
                        <button type="button" class="btn-open-filemanager" data-target="con-video">Seleccionar</button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Clasificación:</label>
                    <input type="text" name="clasificacion" id="con-clasificacion" class="admin-input" placeholder="Ej: Formación, Tutorial...">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Descripción:</label>
                    <textarea name="descripcion_breve" id="con-descripcion_breve" class="admin-input admin-textarea-small" placeholder="Breve resumen del contenido..."></textarea>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Enlace Externo (Opcional):</label>
                    <input type="text" name="enlace_externo" id="con-enlace_externo" class="admin-input" placeholder="https://...">
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
                <div class="col-name">Título / Descripción</div>
                <div class="col-info">Multimedia</div>
                <div class="col-level">Categoría</div>
                <div class="col-actions">Acciones</div>
            </div>

            <?php if (empty($contenidos)): ?>
                <div class="admin-list-row">No hay contenidos registrados.</div>
            <?php endif; ?>

            <?php foreach($contenidos as $con): ?>
                <div class="admin-list-row">
                    <div class="col-id"><?php echo $con['id']; ?></div>
                    <div class="col-name">
                        <div class="bold"><?php echo htmlspecialchars($con['nombre']); ?></div>
                        <div class="small-text text-muted"><?php echo htmlspecialchars(substr($con['descripcion_breve'] ?? '', 0, 60)) . '...'; ?></div>
                    </div>
                    <div class="col-info">
                        <span title="Imagen"><?php echo $con['imagen'] ? '🖼️' : '⚪'; ?></span>
                        <span title="Video"><?php echo $con['video'] ? '🎬' : '⚪'; ?></span>
                    </div>
                    <div class="col-level">
                        <span class="admin-badge badge-category">
                            <?php echo htmlspecialchars($con['categoria_nombre']); ?>
                        </span>
                    </div>
                    <div class="col-actions">
                        <button class="action-edit" onclick='prepareEdit(<?php echo json_encode($con); ?>)'>Editar</button>
                        
                        <form method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este contenido?');">
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

<?php 
/**
 * Solo llamamos a la renderización del Manager de Archivos una vez.
 * El orquestador se encarga de incluir el componente necesario.
 */
$admin->renderFileManager(); 
?>