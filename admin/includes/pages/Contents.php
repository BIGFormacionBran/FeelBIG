<div class="admin-page-container">
    <div class="admin-header-section">
        <h2>Gestión de Contenidos</h2>
    </div>

    <?php if ($status === "success"): ?>
        <div class="admin-status-alert success">✅ Operación realizada con éxito.</div>
    <?php elseif ($status === "error"): ?>
        <div class="admin-status-alert error">❌ <?php echo $message; ?></div>
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
                    <label class="admin-label">Subtítulo:</label>
                    <input type="text" name="subtitulo" id="cont-subtitulo" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Categoría:</label>
                    <select name="id_categoria" id="cont-categoria" required class="admin-select">
                        <option value="">-- Seleccionar Categoría --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">URL Imagen:</label>
                    <input type="text" name="imagen" id="cont-imagen" class="admin-input" placeholder="ej: /assets/img/foto.jpg">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Descripción:</label>
                    <textarea name="descripcion" id="cont-descripcion" class="admin-input admin-textarea-small"></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-submit">Guardar Contenido</button>
                    <button type="button" class="btn-secundario hidden mt-10" id="btn-cancel" onclick="resetForm('content')">Cancelar</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Contenidos Existentes</div>
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
                        <td>
                            <button class="action-edit" onclick='prepareEditContent(<?php echo json_encode($con); ?>)'>Editar</button>
                            <form method="POST" class="inline" onsubmit="return confirm('¿Eliminar contenido?');">
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