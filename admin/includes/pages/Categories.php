<?php
require_once __DIR__ . '/../managers/AdminContentManager.php';
$adminManager = new AdminContentManager();

$status = "";
// Procesar creación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $idPadre = ($_POST['id_padre'] === "null") ? null : $_POST['id_padre'];
    if ($adminManager->createCategory($_POST['nombre'], $idPadre)) {
        $status = "success";
    } else {
        $status = "error";
    }
}

$categorias = $adminManager->listAllCategories();
?>

<div class="admin-container" style="padding: 20px; font-family: sans-serif;">
    <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Gestión de Categorías</h2>
    </div>

    <?php if ($status === "success"): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; border-left: 5px solid #28a745;">
            ✅ Categoría gestionada correctamente.
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        
        <div class="admin-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #eee;">
            <h3 style="margin-top: 0;">Nueva Categoría</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom: 5px; font-weight: bold;">Nombre de la categoría:</label>
                    <input type="text" name="nombre" required placeholder="Ej: Entrenamiento" 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display:block; margin-bottom: 5px; font-weight: bold;">Categoría Padre:</label>
                    <select name="id_padre" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="null">-- Ninguna (Categoría Raíz) --</option>
                        <?php foreach($categorias as $c): ?>
                            <?php if($c['id_padre'] === null): // Solo mostramos principales como padres para evitar líos ?>
                                <option value="<?php echo $c['id']; ?>">
                                    <?php echo htmlspecialchars($c['nombre']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" style="background: #159BD7; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold;">
                    Crear Categoría
                </button>
            </form>
        </div>

        <div class="admin-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #eee;">
            <h3 style="margin-top: 0;">Categorías Existentes</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Nombre</th>
                        <th style="padding: 12px;">Tipo</th>
                        <th style="padding: 12px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categorias as $c): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px; color: #666;"><?php echo $c['id']; ?></td>
                            <td style="padding: 12px; font-weight: 500;">
                                <?php echo ($c['id_padre'] !== null ? "— " : "") . htmlspecialchars($c['nombre']); ?>
                            </td>
                            <td style="padding: 12px;">
                                <span style="font-size: 0.85em; padding: 4px 8px; border-radius: 12px; background: <?php echo $c['id_padre'] === null ? '#e3f2fd' : '#f1f1f1'; ?>">
                                    <?php echo $c['id_padre'] === null ? 'Principal' : 'Subcategoría'; ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <button style="background: none; border: none; color: #159BD7; cursor: pointer; margin-right: 10px;">Editar</button>
                                <button style="background: none; border: none; color: #dc3545; cursor: pointer;">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>