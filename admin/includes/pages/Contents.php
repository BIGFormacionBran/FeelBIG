<?php
require_once __DIR__ . '/../managers/AdminContentManager.php';
$adminManager = new AdminContentManager();

$status = ""; $message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'add') {
        if ($adminManager->createContent($_POST['nombre'], $_POST['descripcion'], $_POST['imagen'], $_POST['id_categoria'])) $status = "success";
        else $status = "error";
    }
    elseif ($action === 'edit') {
        if ($adminManager->updateContent($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['imagen'], $_POST['id_categoria'])) $status = "success";
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
        <div class="admin-status-alert success">✅ Éxito.</div>
    <?php elseif ($status === "error"): ?>
        <div class="admin-status-alert error">❌ Error en la operación.</div>
    <?php endif; ?>

    <div class="admin-flex-layout">
        <div class="admin-card side-form">
            <div class="admin-card-title" id="form-title">Nuevo Contenido</div>
            <form method="POST" id="content-form">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="cont-id" value="">
                
                <div class="admin-form-group">
                    <label class="admin-label">Título:</label>
                    <input type="text" name="nombre" id="cont-nombre" required class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Categoría:</label>
                    <select name="id_categoria" id="cont-categoria" required class="admin-select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">URL Imagen:</label>
                    <input type="text" name="imagen" id="cont-imagen" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Descripción:</label>
                    <textarea name="descripcion" id="cont-descripcion" class="admin-input admin-textarea-small"></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-submit">Guardar</button>
                    <button type="button" class="btn-secundario hidden mt-10" id="btn-cancel" onclick="resetForm('content')">Cancelar</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Contenidos</div>
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
                        <td><span class="admin-badge root"><?php echo htmlspecialchars($con['categoria_nombre']); ?></span></td>
                        <td class="col-actions">
                            <button class="action-edit" onclick='prepareEditContent(<?php echo json_encode($con); ?>)'>Editar</button>
                            <form method="POST" class="inline" onsubmit="return confirm('¿Borrar?');">
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