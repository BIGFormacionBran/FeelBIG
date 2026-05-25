(function() {
    "use strict";

    const logToServer = (msg, lvl = 'INFO') => {
        fetch('/includes/ajax/JsLogger.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ message: msg, level: lvl })
        }).catch(() => {});
    };

    const state = { currentTargetInputId: null, modal: null };

    const ui = {
        scrollTo: (el) => el?.scrollIntoView({ behavior: 'smooth', block: 'start' }),
        toggleHidden: (el, force) => el?.classList.toggle('hidden', force),
        updatePreview: (path, targetId) => {
            logToServer(`UI: Actualizando preview para ID: ${targetId} con ruta: ${path}`);
            const previewContainer = document.getElementById(targetId + '-preview');
            if (!previewContainer) {
                logToServer(`UI: No se encontró el contenedor preview "${targetId}-preview"`, 'ERROR');
                return;
            }
            
            if (!path || path === "null" || path === "") {
                previewContainer.innerHTML = '<span class="text-muted">Sin archivo</span>';
                return;
            }

            const isVideo = path.toLowerCase().match(/\.(mp4|webm|ogg)$/);
            if (isVideo) {
                previewContainer.innerHTML = `
                    <div class="video-preview-thumb">
                        <span class="admin-badge badge-category">VIDEO</span><br>
                        <img src="/assets/admin/img/video-placeholder.png" style="max-height:50px; width:auto;">
                    </div>`;
            } else {
                previewContainer.innerHTML = `<img src="/${path}" class="preview-media" style="max-width:100%; height:auto;">`;
            }
        }
    };

    const fileManager = {
        init() {
            logToServer("FM: Inicializando eventos del Gestor de Archivos.");
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) {
                logToServer("FM: No se encontró el modal #file-manager-modal en el DOM", "ERROR");
                return;
            }

            state.modal.addEventListener('click', (e) => {
                const target = e.target;
                if (target.id === 'fm-close-x' || target.classList.contains('modal-overlay')) {
                    logToServer("FM: Cerrando modal.");
                    ui.toggleHidden(state.modal, true);
                }
                if (target.classList.contains('fm-tab-btn')) {
                    this.switchTab(target);
                }
                if (target.classList.contains('btn-fm-delete')) {
                    e.stopPropagation();
                    this.deleteFile(target.dataset.path, target.closest('.file-item'));
                }
                // BOTÓN EXAMINAR: Restauramos funcionalidad
                if (target.classList.contains('btn-primario') && target.closest('#fm-tab-upload')) {
                    document.getElementById('fm-upload-input').click();
                }

                const item = target.closest('.file-item');
                if (item && !target.classList.contains('btn-fm-delete')) {
                    this.selectFile(item.dataset.path);
                }
            });

            document.querySelectorAll('.btn-open-filemanager').forEach(btn => {
                btn.addEventListener('click', () => {
                    state.currentTargetInputId = btn.dataset.target;
                    const type = state.currentTargetInputId.toLowerCase().includes('video') ? 'videos' : 'images';
                    logToServer(`FM: Abriendo gestor para input: ${state.currentTargetInputId} (Filtro: ${type})`);
                    this.filterGrid(type);
                    ui.toggleHidden(state.modal, false);
                });
            });

            this.initUpload();
        },

        switchTab(btn) {
            logToServer(`FM: Cambiando a pestaña: ${btn.dataset.tab}`);
            state.modal.querySelectorAll('.fm-tab-btn').forEach(b => b.classList.remove('active'));
            state.modal.querySelectorAll('.fm-tab-content').forEach(c => c.classList.add('hidden'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab)?.classList.remove('hidden');
        },

        filterGrid(type) {
            logToServer(`FM: Filtrando grid por tipo: ${type}`);
            state.modal.querySelectorAll('.file-item').forEach(item => {
                ui.toggleHidden(item, item.dataset.type !== type);
            });
        },

        selectFile(path) {
            logToServer(`FM: Archivo seleccionado: ${path}`);
            if (state.currentTargetInputId) {
                const input = document.getElementById(state.currentTargetInputId);
                if (input) {
                    input.value = path;
                    ui.updatePreview(path, state.currentTargetInputId);
                    logToServer(`FM: Valor de "${state.currentTargetInputId}" actualizado.`);
                }
            }
            ui.toggleHidden(state.modal, true);
        },

        deleteFile(path, element) {
            logToServer(`FM: Intento de borrado de archivo: ${path}`);
            if (!confirm('¿Eliminar archivo físicamente del servidor?')) return;

            const formData = new FormData();
            formData.append('action', 'fm-delete-file');
            formData.append('path', path);

            fetch(window.location.pathname, { method: 'POST', body: formData })
                .then(res => res.text()) // Leemos como texto primero para limpiar posibles errores PHP
                .then(text => {
                    try {
                        const data = JSON.parse(text.trim());
                        if (data.success) {
                            location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    } catch (e) {
                        logToServer("FM: Error parseando JSON en borrado: " + text, 'ERROR');
                        location.reload(); // Si llegó aquí y el archivo se borró, recargamos igual
                    }
                })
                .catch(err => alert("Error de comunicación."));
        },

        initUpload() {
            const dropZone = document.getElementById('fm-drop-zone');
            const fileInput = document.getElementById('fm-upload-input');
            if (!dropZone || !fileInput) return;

            ['dragover', 'dragleave', 'drop'].forEach(evt => {
                dropZone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    if (evt === 'dragover') dropZone.classList.add('drag-over');
                    if (evt === 'dragleave' || evt === 'drop') dropZone.classList.remove('drag-over');

                    if (evt === 'drop' && e.dataTransfer.files.length) {
                        this.handleUpload(e.dataTransfer.files[0]);
                    }
                });
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    this.handleUpload(fileInput.files[0]);
                }
            });
        },

        handleUpload(file) {
            const fileType = file.type.startsWith('video/') ? 'videos' : 'images';
            const instruction = document.getElementById('upload-instruction');
            if (instruction) instruction.textContent = `Subiendo ${file.name}...`;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'fm-upload');
            formData.append('type', fileType);

            fetch(window.location.pathname, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(text => {
                try {
                    const data = JSON.parse(text.trim());
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Error en subida: " + data.message);
                    }
                } catch (e) {
                    logToServer("FM: Error parseando JSON en subida: " + text, 'ERROR');
                    location.reload();
                }
            })
            .catch(err => alert("Error de red al subir."));
        }
    };

    window.prepareEdit = (data) => {
        logToServer(`UI: Preparando formulario para edición. ID Entidad: ${data.id}`);
        const container = document.querySelector('.side-form');
        if (!container) return;
        const prefix = (container.dataset.entity === 'Contenido') ? 'con-' : 'cat-';

        document.getElementById('form-action').value = 'edit';
        document.getElementById('form-title').innerText = 'Editando ' + container.dataset.entity;

        Object.entries(data).forEach(([key, value]) => {
            const input = document.getElementById(prefix + key);
            if (input) {
                input.value = (key === 'id_padre' && value === null) ? "null" : (value ?? '');
                if (key === 'imagen' || key === 'video') ui.updatePreview(value, prefix + key);
            }
        });
        ui.toggleHidden(document.getElementById('btn-cancel'), false);
        ui.scrollTo(container);
    };

    window.resetForm = () => {
        location.reload();
    };

    document.addEventListener('DOMContentLoaded', () => {
        fileManager.init();
        const form = document.getElementById('main-entity-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                fetch(window.location.pathname, { method: 'POST', body: new FormData(form) })
                    .then(res => res.text())
                    .then(text => {
                        try {
                            const data = JSON.parse(text.trim());
                            if (data.success) location.reload();
                            else alert('Error: ' + data.message);
                        } catch(e) { location.reload(); }
                    })
                    .catch(() => alert('Error de red.'));
            });
        }
    });
})();