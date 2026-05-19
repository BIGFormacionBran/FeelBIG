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
    <h2>Gestión de Contenidos</h2>

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
                    <label>Título:</label>
                    <input type="text" name="nombre" id="con-nombre" required class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label>Categoría:</label>
                    <select name="id_categoria" id="con-id_categoria" required class="admin-select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Clasificación:</label>
                    <input type="text" name="clasificacion" id="con-clasificacion" class="admin-input" placeholder="Ej: Formación">
                </div>

                <div class="admin-form-group">
                    <label>URL Imagen:</label>
                    <input type="text" name="imagen" id="con-imagen" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label>Video (Nombre archivo):</label>
                    <input type="text" name="video" id="con-video" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label>Descripción Breve:</label>
                    <textarea name="descripcion_breve" id="con-descripcion_breve" class="admin-input admin-textarea-small"></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Enlace Externo:</label>
                    <input type="text" name="enlace_externo" id="con-enlace_externo" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label>Fecha Publicación:</label>
                    <input type="date" name="fecha_publicacion" id="con-fecha_publicacion" class="admin-input" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-submit">Guardar Contenido</button>
                    <button type="button" class="btn-secundario hidden" id="btn-cancel" onclick="resetForm('content')">Cancelar Edición</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Listado de Contenidos</div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($contenidos as $con): ?>
                    <tr>
                        <td><?php echo $con['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($con['nombre']); ?></strong></td>
                        <td><?php echo htmlspecialchars($con['categoria_nombre']); ?></td>
                        <td class="col-actions">
                            <button class="action-edit" onclick='prepareEdit(<?php echo json_encode($con); ?>)'>Editar</button>
                            <form method="POST" class="inline" onsubmit="return confirm('¿Borrar este contenido?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $con['id']; ?>">
                                <button type="submit" class="action-delete-clean">Borrar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>