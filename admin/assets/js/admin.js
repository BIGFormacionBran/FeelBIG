(function() {
    "use strict";

    const state = {
        currentTargetInputId: null,
        modal: null
    };

    const ui = {
        scrollTo: (el) => el?.scrollIntoView({ behavior: 'smooth', block: 'start' }),
        toggleHidden: (el, force) => el?.classList.toggle('hidden', force),
        
        updatePreview: (path, targetId) => {
            const previewContainer = document.getElementById(targetId + '-preview');
            if (!previewContainer) return;
            
            if (!path) {
                previewContainer.innerHTML = '<span class="text-muted">Sin archivo</span>';
                return;
            }

            const isVideo = path.toLowerCase().match(/\.(mp4|webm|ogg)$/);
            if (isVideo) {
                // Previsualización simple con icono/placeholder para no cargar el video real en el form
                previewContainer.innerHTML = `<div class="video-preview-thumb" style="text-align:center;">
                    <span class="admin-badge badge-category" style="margin-bottom:5px; display:inline-block;">VIDEO</span><br>
                    <img src="/assets/admin/img/video-placeholder.png" style="max-height:50px; width:auto;" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM4ODgiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cmVjdCB4PSIyIiB5PSIzIiB3aWR0aD0iMjAiIGhlaWdodD0iMTgiIHJ4PSIyIiByeT0iMiI+PC9yZWN0PjxwYXRoIGQ9Ik03IDJsNSA1IDUtNSI+PC9wYXRoPjwvc3ZnPg=='">
                </div>
                <small style="display:block; font-size:10px; color:#999; margin-top:4px;">${path.split('/').pop()}</small>`;
            } else {
                previewContainer.innerHTML = `<img src="/${path}" class="preview-media" alt="Preview" style="max-width:100%; height:auto;">`;
            }
        },

        switchTab: (tabId, btns, contents) => {
            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById(tabId)?.classList.remove('hidden');
        }
    };

    const fileManager = {
        init() {
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) return; 

            const closeX = document.getElementById('fm-close-x');
            const tabBtns = state.modal.querySelectorAll('.fm-tab-btn');
            const tabContents = state.modal.querySelectorAll('.fm-tab-content');
            const uploadInput = document.getElementById('fm-upload-input');
            const dropZone = document.getElementById('fm-drop-zone');

            document.querySelectorAll('.btn-open-filemanager').forEach(btn => {
                btn.addEventListener('click', () => {
                    state.currentTargetInputId = btn.dataset.target;
                    const type = state.currentTargetInputId.includes('video') ? 'videos' : 'images';
                    this.filterGrid(type);
                    ui.toggleHidden(state.modal, false);
                });
            });

            closeX?.addEventListener('click', () => ui.toggleHidden(state.modal, true));
            state.modal.addEventListener('click', (e) => {
                if (e.target === state.modal) ui.toggleHidden(state.modal, true);
            });

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    ui.switchTab(btn.dataset.tab, tabBtns, tabContents);
                });
            });

            state.modal.addEventListener('click', (e) => {
                const item = e.target.closest('.file-item');
                if (item && !e.target.classList.contains('btn-fm-delete')) {
                    this.selectFile(item.dataset.path);
                }
            });

            state.modal.addEventListener('click', async (e) => {
                const delBtn = e.target.closest('.btn-fm-delete');
                if (delBtn && confirm('¿Eliminar archivo físico permanentemente?')) {
                    await this.deleteFile(delBtn.dataset.path, delBtn.closest('.file-item'));
                }
            });

            uploadInput?.addEventListener('change', () => this.handleUpload(uploadInput.files[0]));

            if (dropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(name => {
                    dropZone.addEventListener(name, (e) => { e.preventDefault(); e.stopPropagation(); });
                });
                ['dragenter', 'dragover'].forEach(name => {
                    dropZone.addEventListener(name, () => dropZone.classList.add('drag-over'));
                });
                ['dragleave', 'drop'].forEach(name => {
                    dropZone.addEventListener(name, () => dropZone.classList.remove('drag-over'));
                });
                dropZone.addEventListener('drop', (e) => {
                    const files = e.dataTransfer.files;
                    if (files.length) this.handleUpload(files[0]);
                });
            }
        },

        filterGrid(type) {
            if (!state.modal) return;
            state.modal.querySelectorAll('.file-item').forEach(item => {
                ui.toggleHidden(item, item.dataset.type !== type);
            });
        },

        selectFile(path) {
            if (state.currentTargetInputId) {
                const input = document.getElementById(state.currentTargetInputId);
                if (input) {
                    input.value = path;
                    ui.updatePreview(path, state.currentTargetInputId);
                }
            }
            ui.toggleHidden(state.modal, true);
        },

        async getVideoThumbnail(file) {
            return new Promise((resolve) => {
                const video = document.createElement('video');
                const canvas = document.createElement('canvas');
                video.preload = 'metadata';
                video.muted = true;
                video.src = URL.createObjectURL(file);
                video.onloadeddata = () => {
                    video.currentTime = 1; 
                };
                video.onseeked = () => {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    const dataUri = canvas.toDataURL('image/jpeg');
                    URL.revokeObjectURL(video.src);
                    resolve(dataUri);
                };
            });
        },

        async deleteFile(path, element) {
            const formData = new FormData();
            formData.append('action', 'fm-delete-file');
            formData.append('path', path);

            try {
                const resp = await fetch(window.location.href, { 
                    method: 'POST', 
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (resp.ok) {
                    element.remove();
                    ['con-imagen', 'con-video'].forEach(id => {
                        const input = document.getElementById(id);
                        if (input && input.value === path) {
                            input.value = '';
                            ui.updatePreview(null, id);
                        }
                    });

                    document.querySelectorAll('.action-edit').forEach(btn => {
                        let data = null;
                        try {
                            if (btn._contentData) {
                                data = btn._contentData;
                            } else {
                                let attr = btn.getAttribute('onclick');
                                if (attr && attr.includes('{')) {
                                    let jsonStr = attr.substring(attr.indexOf('{'), attr.lastIndexOf('}') + 1);
                                    data = JSON.parse(jsonStr);
                                }
                            }

                            if (data) {
                                let updated = false;
                                if (data.imagen === path) { data.imagen = null; updated = true; }
                                if (data.video === path) { data.video = null; updated = true; }

                                if (updated) {
                                    btn._contentData = data;
                                    btn.removeAttribute('onclick');
                                    btn.onclick = function() { window.prepareEdit(data); };
                                    
                                    const row = btn.closest('.admin-list-row');
                                    if (row) {
                                        const badges = row.querySelectorAll('.col-info .admin-badge');
                                        if (data.imagen === null && badges[0]) {
                                            badges[0].classList.remove('badge-success');
                                            badges[0].classList.add('badge-empty');
                                        }
                                        if (data.video === null && badges[1]) {
                                            badges[1].classList.remove('badge-success');
                                            badges[1].classList.add('badge-empty');
                                        }
                                    }
                                }
                            }
                        } catch(e) {}
                    });
                }
            } catch (err) { console.error("Error borrando:", err); }
        },

        async handleUpload(file) {
            if (!file) return;
            
            const targetType = state.currentTargetInputId.includes('video') ? 'videos' : 'images';
            
            // VALIDACIÓN DE FORMATO ANTES DE SUBIR
            if (targetType === 'images' && !file.type.startsWith('image/')) {
                alert("Error: El archivo seleccionado debe ser una imagen.");
                return;
            }
            if (targetType === 'videos' && !file.type.startsWith('video/')) {
                alert("Error: El archivo seleccionado debe ser un vídeo.");
                return;
            }

            const formData = new FormData();
            formData.append('action', 'fm-upload');
            formData.append('type', targetType);
            formData.append('file', file);

            try {
                const resp = await fetch(window.location.href, { 
                    method: 'POST', 
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                const text = await resp.text();
                const jsonStart = text.indexOf('{"');
                if (jsonStart === -1) throw new Error("Respuesta no válida");
                
                const result = JSON.parse(text.substring(jsonStart));
                
                if (result.success) {
                    const grid = document.getElementById('file-grid');
                    if (grid) {
                        const emptyMsg = grid.querySelector('.text-muted');
                        if (emptyMsg && emptyMsg.innerText.includes('No hay archivos')) emptyMsg.remove();
                        
                        let previewHtml;
                        if (targetType === 'images') {
                            previewHtml = `<img src="/${result.path}" alt="Preview">`;
                        } else {
                            // Captura de frame para el vídeo recién subido
                            const thumb = await this.getVideoThumbnail(file);
                            previewHtml = `<img src="${thumb}" class="video-thumb-frame" alt="Video Preview">
                                           <div class="video-overlay-icon">▶</div>`;
                        }
                            
                        const newItem = document.createElement('div');
                        newItem.className = 'file-item';
                        newItem.dataset.path = result.path;
                        newItem.dataset.type = targetType;
                        newItem.innerHTML = `
                            <div class="file-preview">
                                ${previewHtml}
                                <button type="button" class="btn-fm-delete" data-path="${result.path}">&times;</button>
                            </div>
                            <span class="file-name">${result.name}</span>
                        `;
                        grid.prepend(newItem);
                    }

                    this.selectFile(result.path);
                    const browseBtn = state.modal.querySelector('[data-tab="fm-tab-browse"]');
                    browseBtn && browseBtn.click();
                    document.getElementById('fm-upload-input').value = ""; 
                } else {
                    alert("Error al subir archivo");
                }
            } catch (err) { 
                console.error("Error subiendo:", err);
                alert("Error crítico al subir.");
            }
        }
    };

    const contentForm = {
        prepareEdit(data) {
            const container = document.querySelector('.side-form');
            if (!container) return;
            
            const prefix = container.dataset.entity === 'Contenido' ? 'con-' : 'cat-';
            const formAction = document.getElementById('form-action');
            const formTitle = document.getElementById('form-title');

            if (formAction) formAction.value = 'edit';
            if (formTitle) formTitle.innerText = 'Editando ' + container.dataset.entity;

            Object.entries(data).forEach(([key, value]) => {
                const input = document.getElementById(prefix + key);
                if (input) {
                    input.value = (key === 'id_padre' && value === null) ? "null" : (value ?? '');
                    if (key === 'imagen' || key === 'video') ui.updatePreview(value, prefix + key);
                }
            });

            ui.toggleHidden(document.getElementById('btn-cancel'), false);
            ui.scrollTo(container);
        },

        reset(btn) {
            const container = btn.closest('.side-form');
            const form = container.querySelector('form');
            const titleEl = document.getElementById('form-title');
            const actionInput = document.getElementById('form-action');
            
            if (!form) return;
            form.reset();
            
            if(actionInput) actionInput.value = 'add';
            if(titleEl) titleEl.innerText = 'Nueva ' + container.dataset.entity;
            
            container.querySelectorAll('.media-preview-box').forEach(p => p.innerHTML = '<span class="text-muted">Sin archivo</span>');
            ui.toggleHidden(btn, true);
        }
    };

    const init = () => {
        fileManager.init();
        window.prepareEdit = contentForm.prepareEdit;
        window.resetForm = contentForm.reset;
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();