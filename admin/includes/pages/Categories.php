<?php
require_once __DIR__ . '/../managers/ContentManager.php';
$adminManager = new AdminContentManager();

$status = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'add') {
        if ($adminManager->createCategory($_POST['nombre'], $_POST['id_padre'])) $status = "success";
        else { $status = "error"; $message = "Error al crear la categoría."; }
    } 
    elseif ($action === 'edit') {
        if ($adminManager->updateCategory($_POST['id'], $_POST['nombre'], $_POST['id_padre'])) $status = "success";
        else { $status = "error"; $message = "Error al actualizar."; }
    } 
    elseif ($action === 'delete') {
        if ($adminManager->deleteCategory($_POST['id'])) $status = "success";
        else { $status = "error"; $message = "No se pudo eliminar (tiene dependencias)."; }
    }
}
$categorias = $adminManager->listAllCategoriesOrdered();
?>

<div class="admin-page-container">
    <div class="admin-header-section">
        <h2>Gestión de Categorías</h2>
    </div>

    <?php if ($status === "success"): ?>
        <div class="admin-status-alert success">✅ Operación realizada con éxito.</div>
    <?php elseif ($status === "error"): ?>
        <div class="admin-status-alert error">❌ <?php echo $message; ?></div>
    <?php endif; ?>

    <div class="admin-flex-layout">
        <div class="admin-card side-form" data-entity="Categoría">
            <div class="admin-card-title" id="form-title">Nueva Categoría</div>
            <form method="POST" id="category-form">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="cat-id" value="">
                
                <div class="admin-form-group">
                    <label class="admin-label">Nombre:</label>
                    <input type="text" name="nombre" id="cat-nombre" required class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Depende de:</label>
                    <select name="id_padre" id="cat-id_padre" class="admin-select">
                        <option value="null">-- Categoría Principal --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo ($c['id_padre'] !== null ? " — " : "") . htmlspecialchars($c['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-submit">Guardar Categoría</button>
                    <button type="button" class="btn-secundario hidden mt-10" id="btn-cancel" onclick="resetForm(this)">Cancelar Edición</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Listado Jerárquico</div>
            <div class="admin-list-header">
                <div class="col-id">ID</div>
                <div class="col-name">Categoría</div>
                <div class="col-level">Nivel</div>
                <div class="col-actions">Acciones</div>
            </div>

            <?php foreach($categorias as $c): ?>
                <?php $isChild = ($c['id_padre'] !== null); ?>
                <div class="admin-list-row <?php echo $isChild ? 'is-child' : ''; ?>">
                    <div class="col-id"><?php echo $c['id']; ?></div>
                    <div class="col-name <?php echo !$isChild ? 'bold' : ''; ?>">
                        <?php if($isChild): ?><span class="indent-spacer"></span><?php endif; ?>
                        <?php echo htmlspecialchars($c['nombre']); ?>
                    </div>
                    <div class="col-level">
                        <span class="admin-badge <?php echo $isChild ? 'child' : 'root'; ?>">
                            <?php echo $isChild ? 'Subcategoría' : 'Principal'; ?>
                        </span>
                    </div>
                    <div class="col-actions">
                        <button class="action-edit" onclick='prepareEdit(<?php echo json_encode($c); ?>)'>Editar</button>
                        <form method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                            <button type="submit" class="action-delete-clean">Borrar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>