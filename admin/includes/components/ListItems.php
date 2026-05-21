<div class="admin-page-container">
    <div class="admin-header-section">
        <h2><?php echo $config['title']; ?></h2>
    </div>

    <?php include __DIR__ . '/Alerts.php'; ?>

    <div class="admin-flex-layout">
        <?php 
            $entity = $config['entity'];
            $prefix = ($entity === 'Contenido') ? 'con-' : 'cat-';
        ?>
        
        <div class="admin-card side-form" data-entity="<?php echo $entity; ?>">
            <div class="admin-card-title" id="form-title">Gestionar <?php echo $entity; ?></div>
            
            <form method="POST" id="main-entity-form" class="dynamic-form">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="entity_type" value="<?php echo $entity; ?>">
                
                <?php foreach($config['fields'] as $id => $f): ?>
                    <?php if($f['type'] === 'hidden'): ?>
                        <input type="hidden" name="<?php echo $id; ?>" id="<?php echo $prefix . $id; ?>">
                    
                    <?php elseif(in_array($f['type'], ['text', 'number', 'date'])): ?>
                        <div class="admin-form-group">
                            <label class="admin-label"><?php echo $f['label']; ?>:</label>
                            <input type="<?php echo $f['type']; ?>" name="<?php echo $id; ?>" 
                                   id="<?php echo $prefix . $id; ?>" class="admin-input" 
                                   <?php echo ($f['required'] ?? false) ? 'required' : ''; ?>>
                        </div>

                    <?php elseif($f['type'] === 'textarea'): ?>
                        <div class="admin-form-group">
                            <label class="admin-label"><?php echo $f['label']; ?>:</label>
                            <textarea name="<?php echo $id; ?>" id="<?php echo $prefix . $id; ?>" 
                                      class="admin-input admin-textarea-small"></textarea>
                        </div>

                    <?php elseif($f['type'] === 'select'): ?>
                        <div class="admin-form-group">
                            <label class="admin-label"><?php echo $f['label']; ?>:</label>
                            <select name="<?php echo $id; ?>" id="<?php echo $prefix . $id; ?>" class="admin-select">
                                <?php foreach($f['options'] as $value => $label): ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <?php elseif($f['type'] === 'media'): ?>
                        <div class="admin-form-group">
                            <span class="admin-label"><?php echo $f['label']; ?>:</span>
                            <div class="media-selector-wrapper">
                                <input type="hidden" name="<?php echo $id; ?>" id="<?php echo $prefix . $id; ?>">
                                <div id="<?php echo $prefix . $id; ?>-preview" class="media-preview-box">
                                    <span class="text-muted">Sin archivo</span>
                                </div>
                                <button type="button" class="btn-open-filemanager btn-primario" 
                                        data-target="<?php echo $prefix . $id; ?>">Seleccionar</button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="form-buttons">
                    <button type="submit" class="btn-primario" id="btn-save">Guardar</button>
                    <button type="button" id="btn-cancel" class="btn-secundario hidden" onclick="resetForm(this)">Cancelar</button>
                </div>
            </form>
        </div>

        <div class="admin-card main-list">
            <div class="admin-card-title">Registros</div>
            <div class="admin-list-header">
                <?php foreach($config['fields'] as $f): ?>
                    <?php if($f['list'] ?? false): ?>
                        <div class="col-generic"><?php echo $f['label']; ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="col-actions">Acciones</div>
            </div>

            <div class="admin-list-body">
                <?php foreach($config['data'] as $row): ?>
                    <div class="admin-list-row">
                        <?php foreach($config['fields'] as $id => $f): ?>
                            <?php if($f['list'] ?? false): ?>
                                <div class="col-generic">
                                    <?php 
                                        if($id === 'nombre' && !empty($row['id_padre'])) echo '<span class="indent-spacer"></span>';
                                        echo htmlspecialchars($row[$id] ?? ''); 
                                    ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <div class="col-actions">
                            <button type="button" class="action-edit btn-trigger" 
                                    onclick='prepareEdit(<?php echo json_encode($row, JSON_HEX_APOS); ?>)'>Editar</button>
                            
                            <form method="POST" class="inline-delete" onsubmit="return confirm('¿Eliminar registro?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="entity_type" value="<?php echo $entity; ?>">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="action-delete-clean">Borrar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>