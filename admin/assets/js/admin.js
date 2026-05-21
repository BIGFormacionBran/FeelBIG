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
        },

        switchTab: (tabId, contents) => {
            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById(tabId)?.classList.remove('hidden');
        }
    };

    const fileManager = {
        init() {
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) return; 

            document.querySelectorAll('.btn-open-filemanager').forEach(btn => {
                btn.addEventListener('click', () => {
                    state.currentTargetInputId = btn.dataset.target;
                    const type = state.currentTargetInputId.toLowerCase().includes('video') ? 'videos' : 'images';
                    this.filterGrid(type);
                    ui.toggleHidden(state.modal, false);
                });
            });

            document.getElementById('fm-close-x')?.addEventListener('click', () => ui.toggleHidden(state.modal, true));
            
            const tabBtns = state.modal.querySelectorAll('.fm-tab-btn');
            const tabContents = state.modal.querySelectorAll('.fm-tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    ui.switchTab(btn.dataset.tab, tabContents);
                });
            });

            state.modal.addEventListener('click', (e) => {
                const item = e.target.closest('.file-item');
                if (item && !e.target.classList.contains('btn-fm-delete')) {
                    this.selectFile(item.dataset.path);
                }
            });
        },

        filterGrid(type) {
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
        }
    };

    const contentForm = {
        prepareEdit(data) {
            const container = document.querySelector('.side-form');
            if (!container) return;

            const entity = container.dataset.entity;
            const prefix = (entity === 'Contenido') ? 'con-' : 'cat-';
            const formAction = document.getElementById('form-action');
            const formTitle = document.getElementById('form-title');
            const btnCancel = document.getElementById('btn-cancel-edit');

            if (formAction) formAction.value = 'edit';
            if (formTitle) formTitle.innerText = 'Editando ' + entity;

            Object.entries(data).forEach(([key, value]) => {
                const input = document.getElementById(prefix + key);
                if (input) {
                    input.value = (key === 'id_padre' && value === null) ? "null" : (value ?? '');
                    if (key === 'imagen' || key === 'video') {
                        ui.updatePreview(value, prefix + key);
                    }
                }
            });

            ui.toggleHidden(btnCancel, false);
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
            if(titleEl) titleEl.innerText = 'Gestionar ' + container.dataset.entity;
            
            container.querySelectorAll('.media-preview-box').forEach(p => {
                p.innerHTML = '<span class="text-muted">Sin archivo</span>';
            });
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