<?php
require_once __DIR__ . '/../managers/AdminContentManager.php';
$adminManager = new AdminContentManager();

$status = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if ($adminManager->createCategory($_POST['nombre'], $_POST['padre'])) {
        $status = "success";
    } else {
        $status = "error";
    }
}

$categorias = $adminManager->listAllCategories();
?>

<div class="admin-content" style="padding: 20px;">
    <h2>Gestión de Categorías</h2>
    
    <?php if ($status === "success"): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            Categoría creada correctamente.
        </div>
    <?php endif; ?>

    <form method="POST" style="background: #f4f4f4; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
        <input type="hidden" name="action" value="add">
        <div style="margin-bottom: 10px;">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label>Padre (Opcional):</label><br>
            <select name="padre" style="width: 100%; padding: 8px;">
                <?php foreach($categorias as $c): ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php 
                            $prefix = ($c['id_padre'] !== null) ? "— " : "";
                            echo $prefix . htmlspecialchars($c['nombre']); 
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" style="background: #159BD7; color: white; border: none; padding: 10px 20px; cursor: pointer;">Guardar</button>
    </form>
</div>