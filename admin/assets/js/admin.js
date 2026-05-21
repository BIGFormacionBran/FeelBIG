(function() {
    "use strict";

    const logToServer = (msg, lvl = 'INFO') => {
        fetch('/includes/ajax/JsLogger.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ message: `[JS_CLIENT]: ${msg}`, level: lvl })
        }).catch(() => {});
    };

    const state = { currentTargetInputId: null, modal: null };

    const ui = {
        scrollTo: (el) => el?.scrollIntoView({ behavior: 'smooth', block: 'start' }),
        toggleHidden: (el, force) => el?.classList.toggle('hidden', force),
        updatePreview: (path, targetId) => {
            logToServer(`Actualizando preview para ${targetId}. Path: ${path}`);
            const previewContainer = document.getElementById(targetId + '-preview');
            if (!previewContainer) return;
            
            if (!path || path === "null" || path === "") {
                previewContainer.innerHTML = '<span class="text-muted">Sin archivo</span>';
                return;
            }

            const isVideo = path.toLowerCase().match(/\.(mp4|webm|ogg)$/);
            if (isVideo) {
                previewContainer.innerHTML = `
                    <div class="video-preview-thumb" style="text-align:center;">
                        <span class="admin-badge badge-category" style="margin-bottom:5px; display:inline-block;">VIDEO</span><br>
                        <img src="/assets/admin/img/video-placeholder.png" style="max-height:50px; width:auto;">
                    </div>
                    <small style="display:block; font-size:10px; color:#999; margin-top:4px;">${path.split('/').pop()}</small>`;
            } else {
                previewContainer.innerHTML = `<img src="/${path}" class="preview-media" alt="Preview" style="max-width:100%; height:auto;">`;
            }
        }
    };

    const fileManager = {
        init() {
            logToServer("Inicializando File Manager");
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) return;

            state.modal.addEventListener('click', (e) => {
                const target = e.target;
                if (target.id === 'fm-close-x' || target.classList.contains('modal-overlay')) {
                    logToServer("Cerrando modal");
                    ui.toggleHidden(state.modal, true);
                }
                if (target.classList.contains('fm-tab-btn')) {
                    logToServer(`Cambiando a pestaña: ${target.dataset.tab}`);
                    this.switchTab(target);
                }
                if (target.classList.contains('btn-fm-delete')) {
                    e.stopPropagation();
                    this.deleteFile(target.dataset.path, target.closest('.file-item'));
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
                    logToServer(`Abriendo FM para input: ${state.currentTargetInputId}, Filtro: ${type}`);
                    this.filterGrid(type);
                    ui.toggleHidden(state.modal, false);
                });
            });

            this.initUpload();
        },

        switchTab(btn) {
            const tabs = state.modal.querySelectorAll('.fm-tab-btn');
            const contents = state.modal.querySelectorAll('.fm-tab-content');
            tabs.forEach(b => b.classList.remove('active'));
            contents.forEach(c => c.classList.add('hidden'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab)?.classList.remove('hidden');
        },

        filterGrid(type) {
            state.modal.querySelectorAll('.file-item').forEach(item => {
                ui.toggleHidden(item, item.dataset.type !== type);
            });
        },

        selectFile(path) {
            logToServer(`Archivo seleccionado: ${path} para input: ${state.currentTargetInputId}`);
            if (state.currentTargetInputId) {
                const input = document.getElementById(state.currentTargetInputId);
                if (input) {
                    input.value = path;
                    ui.updatePreview(path, state.currentTargetInputId);
                }
            }
            ui.toggleHidden(state.modal, true);
        },

        deleteFile(path, element) {
            logToServer(`Intento de borrado físico: ${path}`);
            if (!confirm('¿Eliminar archivo?')) return;
            
            const formData = new FormData();
            formData.append('action', 'fm-delete-file');
            formData.append('path', path);

            fetch(window.location.pathname, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    logToServer(`Respuesta borrado: ${JSON.stringify(data)}`);
                    if (data.success) element.remove();
                    else alert("Error: " + data.message);
                });
        },

        initUpload() {
            const dropZone = document.getElementById('fm-drop-zone');
            const fileInput = document.getElementById('fm-upload-input');
            if (!dropZone || !fileInput) return;

            ['dragover', 'dragleave', 'drop'].forEach(evt => {
                dropZone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    if (evt === 'drop' && e.dataTransfer.files.length) {
                        logToServer(`Archivo dropeado: ${e.dataTransfer.files[0].name}`);
                        this.handleUpload(e.dataTransfer.files[0]);
                    }
                });
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    logToServer(`Archivo seleccionado via input: ${fileInput.files[0].name}`);
                    this.handleUpload(fileInput.files[0]);
                }
            });
        },

        handleUpload(file) {
            logToServer(`Iniciando Fetch Upload: ${file.name}, Size: ${file.size}, Type: ${file.type}`);
            const instruction = document.getElementById('upload-instruction');
            if (instruction) instruction.textContent = `Subiendo ${file.name}...`;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'fm-upload');
            formData.append('type', file.type.startsWith('video/') ? 'videos' : 'images');

            fetch(window.location.href, { method: 'POST', body: formData })
            .then(res => {
                logToServer(`Status HTTP Upload: ${res.status}`);
                return res.json();
            })
            .then(data => {
                logToServer(`Respuesta JSON Upload: ${JSON.stringify(data)}`);
                if (data.success) {
                    setTimeout(() => location.reload(), 500);
                } else {
                    instruction.textContent = `Error: ${data.message}`;
                }
            })
            .catch(err => { 
                logToServer(`ERROR CRITICO JS UPLOAD: ${err.message}`, 'ERROR');
                instruction.textContent = 'Error de conexión.'; 
            });
        }
    };

    const contentForm = {
        prepareEdit(data) {
            logToServer(`Preparando edición. Datos recibidos: ${JSON.stringify(data)}`);
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
        }
    };

    window.prepareEdit = contentForm.prepareEdit;
    window.resetForm = (btn) => {
        logToServer("Reset form click");
        location.reload(); 
    };
    document.addEventListener('DOMContentLoaded', () => fileManager.init());
})();