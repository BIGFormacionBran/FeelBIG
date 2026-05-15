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

<div style="padding: 20px; font-family: sans-serif; color: #333;">
    <div style="margin-bottom: 30px;">
        <h2 style="margin: 0;">Gestión de Categorías</h2>
    </div>

    <?php if ($status === "success"): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; border-left: 5px solid #28a745;">
            ✅ Operación realizada con éxito.
        </div>
    <?php elseif ($status === "error"): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border-left: 5px solid #dc3545;">
            ❌ Error al procesar la categoría (posible nombre duplicado).
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px;">
        
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; height: fit-content;">
            <div style="font-weight: bold; font-size: 1.1em; margin-bottom: 20px;">Nueva Categoría</div>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                
                <div style="margin-bottom: 15px;">
                    <div style="margin-bottom: 5px; font-size: 0.9em; color: #666;">Nombre:</div>
                    <input type="text" name="nombre" required placeholder="Nombre de categoría..." 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="margin-bottom: 5px; font-size: 0.9em; color: #666;">Depende de (Categoría Padre):</div>
                    <select name="id_padre" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white; font-family: monospace;">
                        <option value="null">-- Categoría Principal (Raíz) --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php 
                                    // Añadimos guiones visuales en el select según sea subcategoría o no
                                    echo ($c['id_padre'] !== null ? "  — " : "") . htmlspecialchars($c['nombre']); 
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" style="background: #159BD7; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold;">
                    Guardar Categoría
                </button>
            </form>
        </div>

        <div style="flex: 2; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee;">
            <div style="font-weight: bold; font-size: 1.1em; margin-bottom: 20px;">Listado Jerárquico</div>
            
            <div style="display: flex; background: #f8f9fa; padding: 12px; border-radius: 4px; font-weight: bold; font-size: 0.9em; border-bottom: 2px solid #eee;">
                <div style="width: 50px;">ID</div>
                <div style="flex: 2;">Categoría</div>
                <div style="flex: 1;">Nivel</div>
                <div style="width: 120px; text-align: right;">Acciones</div>
            </div>

            <?php foreach($categorias as $c): ?>
                <div style="display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f1f1f1; font-size: 0.95em; <?php echo $c['id_padre'] ? 'background: #fafafa;' : ''; ?>">
                    <div style="width: 50px; color: #999; font-size: 0.8em;"><?php echo $c['id']; ?></div>
                    
                    <div style="flex: 2; font-weight: <?php echo $c['id_padre'] ? 'normal' : 'bold'; ?>;">
                        <?php if($c['id_padre']): ?>
                            <span style="margin: 0 13px;"></span>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($c['nombre']); ?>
                    </div>

                    <div style="flex: 1;">
                        <div style="display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 0.75em; background: <?php echo $c['id_padre'] ? '#eee' : '#e3f2fd'; ?>; color: <?php echo $c['id_padre'] ? '#666' : '#0d47a1'; ?>;">
                            <?php echo $c['id_padre'] ? 'Subcategoría' : 'Principal'; ?>
                        </div>
                    </div>

                    <div style="width: 120px; text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                        <div style="color: #159BD7; cursor: pointer; font-size: 0.85em;">Editar</div>
                        <div style="color: #dc3545; cursor: pointer; font-size: 0.85em;">Borrar</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>