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
                previewContainer.innerHTML = `<video src="/${path}" class="preview-media" style="max-width:100%; height:auto;" controls></video>`;
            } else {
                previewContainer.innerHTML = `<img src="/${path}" class="preview-media" alt="Preview" style="max-width:100%; height:auto;">`;
            }
        },

        switchTab: (tabId, btns, contents) => {
            contents.forEach(c => c.classList.add('hidden'));
            btns.forEach(b => b.classList.remove('active'));
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
        },

        filterGrid(type) {
            if (!state.modal) return;
            state.modal.querySelectorAll('.file-item').forEach(item => {
                ui.toggleHidden(item, item.dataset.type !== type);
            });
            const instruction = document.getElementById('upload-instruction');
            if(instruction) instruction.innerText = `Subir ${type === 'videos' ? 'Video' : 'Imagen'}`;
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
                if (resp.ok) element.remove();
            } catch (err) { console.error("Error borrando:", err); }
        },

        async handleUpload(file) {
            if (!file) return;
            const type = state.currentTargetInputId.includes('video') ? 'videos' : 'images';
            const formData = new FormData();
            formData.append('action', 'fm-upload');
            formData.append('type', type);
            formData.append('file', file);

            try {
                const resp = await fetch(window.location.href, { 
                    method: 'POST', 
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await resp.json();
                
                if (result.success) {
                    // 1. Añadir al grid visualmente (opcional, ya que lo seleccionamos directo)
                    // 2. Seleccionar automáticamente el archivo subido
                    this.selectFile(result.path);
                    // 3. Volver a la pestaña de explorar para la próxima vez
                    const browseBtn = state.modal.querySelector('[data-tab="fm-tab-browse"]');
                    browseBtn && browseBtn.click();
                    document.getElementById('fm-upload-input').value = ""; 
                } else {
                    alert("Error al subir archivo");
                }
            } catch (err) { console.error("Error subiendo:", err); }
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