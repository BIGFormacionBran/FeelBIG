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
                    </div>`;
            } else {
                previewContainer.innerHTML = `<img src="/${path}" class="preview-media" style="max-width:100%; height:auto;">`;
            }
        }
    };

    const fileManager = {
        init() {
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) return;

            state.modal.addEventListener('click', (e) => {
                const target = e.target;
                if (target.id === 'fm-close-x' || target.classList.contains('modal-overlay')) {
                    ui.toggleHidden(state.modal, true);
                }
                if (target.classList.contains('fm-tab-btn')) {
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
                    this.filterGrid(type);
                    ui.toggleHidden(state.modal, false);
                });
            });

            this.initUpload();
        },

        switchTab(btn) {
            state.modal.querySelectorAll('.fm-tab-btn').forEach(b => b.classList.remove('active'));
            state.modal.querySelectorAll('.fm-tab-content').forEach(c => c.classList.add('hidden'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab)?.classList.remove('hidden');
        },

        filterGrid(type) {
            state.modal.querySelectorAll('.file-item').forEach(item => {
                ui.toggleHidden(item, item.dataset.type !== type);
            });
        },

        selectFile(path) {
            logToServer(`Seleccionando archivo del gestor: ${path}`);
            if (state.currentTargetInputId) {
                const input = document.getElementById(state.currentTargetInputId);
                if (input) {
                    input.value = path;
                    ui.updatePreview(path, state.currentTargetInputId);
                    logToServer(`Input "${state.currentTargetInputId}" actualizado con la ruta del archivo.`);
                }
            }
            ui.toggleHidden(state.modal, true);
        },

        deleteFile(path, element) {
            logToServer(`Usuario intentando eliminar archivo: ${path}`);
            if (!confirm('¿Eliminar archivo físicamente del servidor?')) {
                logToServer(`Eliminación cancelada por el usuario: ${path}`);
                return;
            }

            logToServer(`Confirmación aceptada. Enviando petición de borrado para: ${path}`);
            const formData = new FormData();
            formData.append('action', 'fm-delete-file');
            formData.append('path', path);

            fetch(window.location.href, { method: 'POST', body: formData })
                .then(res => {
                    logToServer(`Servidor respondió a borrado HTTP status: ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        logToServer(`BORRADO EXITOSO - El archivo ${path} ha sido eliminado del servidor.`);
                        element.remove();
                    } else {
                        logToServer(`BORRADO FALLIDO - El servidor devolvió error: ${data.message || 'Error desconocido'}`, 'ERROR');
                        alert("Error: " + (data.message || "No se pudo borrar"));
                    }
                })
                .catch(err => {
                    logToServer(`ERROR CRÍTICO (Red) al intentar borrar ${path}: ${err.message}`, 'ERROR');
                    alert("Error de comunicación con el servidor.");
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
                        logToServer(`Archivo soltado (drag&drop): ${e.dataTransfer.files[0].name}`);
                        this.handleUpload(e.dataTransfer.files[0]);
                    }
                });
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    logToServer(`Archivo seleccionado vía input: ${fileInput.files[0].name}`);
                    this.handleUpload(fileInput.files[0]);
                }
            });
        },

        handleUpload(file) {
            const fileType = file.type.startsWith('video/') ? 'videos' : 'images';
            logToServer(`Iniciando proceso de subida. Archivo: ${file.name}, Tamaño: ${file.size} bytes, Tipo: ${file.type}`);
            
            const instruction = document.getElementById('upload-instruction');
            if (instruction) instruction.textContent = `Subiendo ${file.name}...`;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'fm-upload');
            formData.append('type', fileType);

            logToServer(`Enviando POST multipart/form-data a ${window.location.href} con acción fm-upload`);

            fetch(window.location.href, { method: 'POST', body: formData })
            .then(res => {
                logToServer(`Servidor respondió a subida HTTP status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    logToServer(`SUBIDA EXITOSA - Archivo guardado: ${file.name}. Recargando interfaz...`);
                    location.reload();
                } else {
                    logToServer(`SUBIDA FALLIDA - Servidor rechazó el archivo ${file.name}. Mensaje: ${data.message}`, 'ERROR');
                    if (instruction) instruction.textContent = `Error: ${data.message}`;
                    alert("Error en subida: " + data.message);
                }
            })
            .catch(err => { 
                logToServer(`ERROR CRÍTICO (Red) durante la subida de ${file.name}: ${err.message}`, 'ERROR');
                if (instruction) instruction.textContent = 'Error de conexión.'; 
                alert("Error de conexión al subir archivo.");
            });
        }
    };

    window.prepareEdit = (data) => {
        logToServer(`Preparando edición de entidad. ID: ${data.id || 'N/A'}`);
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
        logToServer('Reseteando formulario (reload)');
        location.reload();
    };

    document.addEventListener('DOMContentLoaded', () => {
        logToServer('DOM cargado - Inicializando gestor de archivos.');
        fileManager.init();
        const form = document.getElementById('main-entity-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                logToServer('Enviando formulario principal de entidad al servidor...');
                
                fetch(window.location.href, { method: 'POST', body: new FormData(form) })
                    .then(res => {
                        logToServer(`Respuesta servidor (Form principal) HTTP: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            logToServer('ENTIDAD ACTUALIZADA - Cambios guardados correctamente.');
                            location.reload();
                        } else {
                            logToServer(`ENTIDAD NO ACTUALIZADA - Error del servidor: ${data.message}`, 'ERROR');
                            alert('Error al guardar: ' + data.message);
                        }
                    })
                    .catch(err => {
                        logToServer(`ERROR CRÍTICO (Red) al enviar formulario principal: ${err.message}`, 'ERROR');
                        alert('Error de red: ' + err.message);
                    });
            });
        }
    });
})();