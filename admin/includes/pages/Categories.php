<?php
require_once __DIR__ . '/../managers/AdminContentManager.php';
$adminManager = new AdminContentManager();

$status = "";
$message = "";

// Procesamiento de Acciones (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        if ($adminManager->createCategory($_POST['nombre'], $_POST['id_padre'])) {
            $status = "success";
        } else {
            $status = "error";
            $message = "Error al crear la categoría (posible nombre duplicado).";
        }
    } 
    elseif ($action === 'edit') {
        if ($adminManager->updateCategory($_POST['id'], $_POST['nombre'], $_POST['id_padre'])) {
            $status = "success";
        } else {
            $status = "error";
            $message = "Error al actualizar la categoría.";
        }
    } 
    elseif ($action === 'delete') {
        if ($adminManager->deleteCategory($_POST['id'])) {
            $status = "success";
        } else {
            $status = "error";
            $message = "No se pudo eliminar. Verifique que la categoría no tenga subcategorías o contenidos vinculados.";
        }
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
            ❌ <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="admin-flex-layout">
        <div class="admin-card side-form">
            <div class="admin-card-title" id="form-title">Nueva Categoría</div>
            <form method="POST" id="category-form">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="cat-id" value="">
                
                <div class="admin-form-group">
                    <div class="admin-label">Nombre:</div>
                    <input type="text" name="nombre" id="cat-nombre" required placeholder="Nombre de categoría..." class="admin-input">
                </div>

                <div class="admin-form-group">
                    <div class="admin-label">Depende de (Categoría Padre):</div>
                    <select name="id_padre" id="cat-padre" class="admin-select">
                        <option value="null">-- Categoría Principal (Raíz) --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo ($c['id_padre'] !== null ? " — " : "") . htmlspecialchars($c['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-submit">Guardar Categoría</button>
                    <button type="button" class="btn-secundario" id="btn-cancel" style="display:none; margin-top:10px;" onclick="resetForm()">Cancelar Edición</button>
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
                        <button class="action-edit" onclick='prepareEdit(<?php echo json_encode($c); ?>)'>
                            Editar
                        </button>

                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría? Si tiene hijos o contenidos, la base de datos podría denegar la acción.');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                            <button type="submit" class="action-delete" style="border:none; background:none; cursor:pointer;">Borrar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
/**
 * Prepara el formulario lateral para editar una categoría existente
 */
function prepareEdit(cat) {
    document.getElementById('form-title').innerText = 'Editar Categoría';
    document.getElementById('form-action').value = 'edit';
    document.getElementById('cat-id').value = cat.id;
    document.getElementById('cat-nombre').value = cat.nombre;
    
    // Ajustar el select del padre
    const padreSelect = document.getElementById('cat-padre');
    padreSelect.value = (cat.id_padre === null) ? "null" : cat.id_padre;
    
    // Cambiar visualización de botones
    document.getElementById('btn-submit').innerText = 'Actualizar Cambios';
    document.getElementById('btn-cancel').style.display = 'block';
    
    // Hacer scroll suave al formulario si se está en móvil
    document.querySelector('.side-form').scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restablece el formulario al estado de "Nueva Categoría"
 */
function resetForm() {
    document.getElementById('form-title').innerText = 'Nueva Categoría';
    document.getElementById('form-action').value = 'add';
    document.getElementById('cat-id').value = '';
    document.getElementById('category-form').reset();
    document.getElementById('btn-submit').innerText = 'Guardar Categoría';
    document.getElementById('btn-cancel').style.display = 'none';
}
</script>