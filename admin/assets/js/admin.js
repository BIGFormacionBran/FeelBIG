(function() {
    "use strict";

    const logToServer = (msg, lvl = 'INFO') => {
        fetch('/includes/ajax/JsLogger.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ message: msg, level: lvl })
        }).catch(() => {});
    };

    logToServer("JS_LIFECYCLE: Script cargado e iniciando IIFE");

    const state = {
        currentTargetInputId: null,
        modal: null
    };

    const ui = {
        scrollTo: (el) => {
            logToServer(`UI_ACTION: Scroll hacia el elemento ${el?.id || 'desconocido'}`);
            el?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
        toggleHidden: (el, force) => {
            logToServer(`UI_ACTION: ToggleHidden en ${el?.id || el?.className}. Force: ${force}`);
            el?.classList.toggle('hidden', force);
        },
        
        updatePreview: (path, targetId) => {
            logToServer(`PREVIEW_FLOW: Intentando actualizar preview. Target: ${targetId}, Path: ${path}`);
            const previewContainer = document.getElementById(targetId + '-preview');
            
            if (!previewContainer) {
                logToServer(`PREVIEW_ERROR: No se encontró contenedor para ${targetId}-preview`, 'ERROR');
                return;
            }
            
            if (!path || path === "null" || path === "") {
                logToServer(`PREVIEW_STATE: Ruta vacía o nula. Limpiando preview.`);
                previewContainer.innerHTML = '<span class="text-muted">Sin archivo</span>';
                return;
            }

            const isVideo = path.toLowerCase().match(/\.(mp4|webm|ogg)$/);
            logToServer(`PREVIEW_TYPE: El archivo es video? ${isVideo ? 'SÍ' : 'NO'}`);

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
            logToServer(`UI_ACTION: Cambio de pestaña a ${tabId}`);
            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById(tabId)?.classList.remove('hidden');
        }
    };

    const fileManager = {
        init() {
            state.modal = document.getElementById('file-manager-modal');
            logToServer(`FM_INIT: Buscando modal... ${state.modal ? 'Encontrado' : 'NOT FOUND'}`);
            if (!state.modal) return; 

            document.querySelectorAll('.btn-open-filemanager').forEach(btn => {
                btn.addEventListener('click', () => {
                    state.currentTargetInputId = btn.dataset.target;
                    logToServer(`FM_EVENT: Abriendo FM. Target input: ${state.currentTargetInputId}`);
                    const type = state.currentTargetInputId.toLowerCase().includes('video') ? 'videos' : 'images';
                    this.filterGrid(type);
                    ui.toggleHidden(state.modal, false);
                });
            });

            document.getElementById('fm-close-x')?.addEventListener('click', () => {
                logToServer("FM_EVENT: Cierre manual del modal");
                ui.toggleHidden(state.modal, true);
            });
            
            const tabBtns = state.modal.querySelectorAll('.fm-tab-btn');
            const tabContents = state.modal.querySelectorAll('.fm-tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    logToServer(`FM_EVENT: Click en tab ${btn.dataset.tab}`);
                    tabBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    ui.switchTab(btn.dataset.tab, tabContents);
                });
            });

            state.modal.addEventListener('click', (e) => {
                const item = e.target.closest('.file-item');
                if (item && !e.target.classList.contains('btn-fm-delete')) {
                    logToServer(`FM_EVENT: Item clickeado en grid. Path: ${item.dataset.path}`);
                    this.selectFile(item.dataset.path);
                }
            });
        },

        filterGrid(type) {
            logToServer(`FM_LOGIC: Filtrando grid para tipo: ${type}`);
            state.modal.querySelectorAll('.file-item').forEach(item => {
                ui.toggleHidden(item, item.dataset.type !== type);
            });
        },

        selectFile(path) {
            logToServer(`FM_ACTION: Seleccionando archivo final -> Path: ${path} | Input: ${state.currentTargetInputId}`);
            if (state.currentTargetInputId) {
                const input = document.getElementById(state.currentTargetInputId);
                if (input) {
                    input.value = path;
                    ui.updatePreview(path, state.currentTargetInputId);
                } else {
                    logToServer(`FM_ERROR: No se encontró el elemento input ${state.currentTargetInputId}`, 'ERROR');
                }
            }
            ui.toggleHidden(state.modal, true);
        }
    };

    const contentForm = {
        prepareEdit(data) {
            logToServer(`FORM_ACTION: Iniciando prepareEdit. Datos: ${JSON.stringify(data)}`);
            const container = document.querySelector('.side-form');
            if (!container) {
                logToServer("FORM_ERROR: No se encontró .side-form", "ERROR");
                return;
            }

            const entity = container.dataset.entity;
            const prefix = (entity === 'Contenido') ? 'con-' : 'cat-';
            logToServer(`FORM_CONTEXT: Entidad ${entity}, Prefijo ${prefix}`);

            const formAction = document.getElementById('form-action');
            const formTitle = document.getElementById('form-title');
            const btnCancel = document.getElementById('btn-cancel-edit');

            if (formAction) formAction.value = 'edit';
            if (formTitle) formTitle.innerText = 'Editando ' + entity;

            Object.entries(data).forEach(([key, value]) => {
                const input = document.getElementById(prefix + key);
                logToServer(`FORM_MAP: Campo [${key}] -> Valor [${value}]`);
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
            logToServer("FORM_ACTION: Reset solicitado por el usuario");
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
        logToServer("JS_LIFECYCLE: DOMContentLoaded. Inicializando managers.");
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