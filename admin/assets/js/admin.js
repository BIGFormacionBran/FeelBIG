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
                previewContainer.innerHTML = `<video src="/${path}" class="preview-media"></video>`;
            } else {
                previewContainer.innerHTML = `<img src="/${path}" class="preview-media" alt="Preview">`;
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

            const tabBtns = state.modal.querySelectorAll('.fm-tab-btn');
            const tabContents = state.modal.querySelectorAll('.fm-tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    ui.switchTab(btn.dataset.tab, tabBtns, tabContents);
                    btn.classList.add('active');
                });
            });

            document.addEventListener('click', (e) => {
                const openBtn = e.target.closest('.btn-open-filemanager');
                if (openBtn) {
                    state.currentTargetInputId = openBtn.getAttribute('data-target');
                    this.highlightCurrent();
                    ui.toggleHidden(state.modal, false);
                    return;
                }

                if (e.target.closest('.btn-close-modal') || e.target === state.modal) {
                    ui.toggleHidden(state.modal, true);
                    return;
                }

                const fileItem = e.target.closest('.file-item');
                if (fileItem && !e.target.closest('.btn-fm-delete')) {
                    const path = fileItem.getAttribute('data-path');
                    this.selectFile(path);
                    return;
                }

                const deleteBtn = e.target.closest('.btn-fm-delete');
                if (deleteBtn) {
                    e.stopPropagation();
                    this.deleteFile(deleteBtn.dataset.path, deleteBtn);
                }
            });

            document.getElementById('fm-upload-input')?.addEventListener('change', (e) => {
                if (e.target.files.length > 0) this.uploadFile(e.target.files[0]);
            });
        },

        highlightCurrent() {
            const currentPath = document.getElementById(state.currentTargetInputId)?.value;
            state.modal.querySelectorAll('.file-item').forEach(item => {
                item.classList.toggle('is-selected', item.getAttribute('data-path') === currentPath);
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

        async uploadFile(file) {
            const formData = new FormData();
            formData.append('action', 'fm-upload');
            formData.append('file', file);
            formData.append('type', state.currentTargetInputId?.includes('video') ? 'videos' : 'images');

            const response = await fetch(window.location.href, { method: 'POST', body: formData });
            if (response.ok) location.reload();
        },

        async deleteFile(path, btn) {
            if (!confirm('Eliminar archivo del servidor?')) return;
            
            const formData = new FormData();
            formData.append('action', 'fm-delete-file');
            formData.append('path', path);

            const response = await fetch(window.location.href, { method: 'POST', body: formData });
            if (response.ok) {
                if (document.getElementById(state.currentTargetInputId)?.value === path) {
                    document.getElementById(state.currentTargetInputId).value = "";
                    ui.updatePreview("", state.currentTargetInputId);
                }
                btn.closest('.file-item').remove();
            }
        }
    };

    const contentForm = {
        prepareEdit(data) {
            const form = document.querySelector('.side-form form');
            if (!form) return;

            const container = form.closest('.side-form');
            const titleEl = document.getElementById('form-title');
            const actionInput = document.getElementById('form-action');
            const idInput = form.querySelector('input[id*="-id"]');
            const prefix = idInput ? idInput.id.split('-')[0] + '-' : '';

            // CAMBIO CLAVE: Cambiar acción a edit y actualizar título
            if(actionInput) actionInput.value = 'edit';
            if(titleEl) titleEl.innerText = 'Editar ' + container.dataset.entity;

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
            
            // Restaurar a modo "Añadir"
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

    document.addEventListener("DOMContentLoaded", init);
})();