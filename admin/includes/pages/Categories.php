<?php
require_once __DIR__ . '/../managers/AdminContentManager.php';
$adminManager = new AdminContentManager();

$status = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if ($adminManager->createCategory($_POST['nombre'], $_POST['id_padre'])) {
        $status = "success";
    } else {
        $status = "error";
    }
}

$categorias = $adminManager->listAllCategoriesOrdered();
?>

<div class="admin-page-container">
    <div class="admin-header-section">
        <h2>Gestión de Categorías</h2>
    </div>
    <?php if ($status === "success"): ?>
        <div class="admin-status-alert success">
            ✅ Operación realizada con éxito.
        </div>
    <?php elseif ($status === "error"): ?>
        <div class="admin-status-alert error">
            ❌ Error al procesar la categoría (posible nombre duplicado).
        </div>
    <?php endif; ?>
    <div class="admin-flex-layout">
        <div class="admin-card side-form">
            <div class="admin-card-title">Nueva Categoría</div>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="admin-form-group">
                    <div class="admin-label">Nombre:</div>
                    <input type="text" name="nombre" required placeholder="Nombre de categoría..." class="admin-input">
                </div>
                <div class="admin-form-group">
                    <div class="admin-label">Depende de (Categoría Padre):</div>
                    <select name="id_padre" class="admin-select">
                        <option value="null">-- Categoría Principal (Raíz) --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo ($c['id_padre'] !== null ? " — " : "") . htmlspecialchars($c['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primario">Guardar Categoría</button>
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
                        <?php if($isChild): ?>
                            <span class="indent-spacer"></span>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($c['nombre']); ?>
                    </div>

                    <div class="col-level">
                        <div class="admin-badge <?php echo $isChild ? 'child' : 'root'; ?>">
                            <?php echo $isChild ? 'Subcategoría' : 'Principal'; ?>
                        </div>
                    </div>

                    <div class="col-actions">
                        <div class="action-edit">Editar</div>
                        <div class="action-delete">Borrar</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>