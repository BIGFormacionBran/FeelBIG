(function() {
    "use strict";

    const state = {
        currentTargetInputId: null,
        modal: null
    };

    const ui = {
        scrollTo: (el) => el?.scrollIntoView({ behavior: 'smooth', block: 'start' }),
        toggleHidden: (el, force) => el?.classList.toggle('hidden', force)
    };

    const logRemote = (msg, level = 'ERROR') => {
        fetch('/includes/ajax/JsLogger.php', {
            method: 'POST',
            body: JSON.stringify({ message: msg, level: level })
        }).catch(() => {});
    };

    const fileManager = {
        init() {
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) return;

            document.getElementById('fm-upload-input')?.addEventListener('change', (e) => {
                if (e.target.files.length > 0) this.uploadFile(e.target.files[0]);
            });

            document.addEventListener('click', (e) => {
                const fileItem = e.target.closest('.file-item');
                if (fileItem && !e.target.closest('.btn-fm-delete')) {
                    const path = fileItem.getAttribute('data-path');
                    if (state.currentTargetInputId) {
                        const input = document.getElementById(state.currentTargetInputId);
                        if (input) input.value = path;
                    }
                    ui.toggleHidden(state.modal, true);
                    return;
                }

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
            });
        },

        highlightCurrent() {
            const currentPath = document.getElementById(state.currentTargetInputId)?.value;
            document.querySelectorAll('.file-item').forEach(item => {
                item.classList.toggle('is-selected', item.getAttribute('data-path') === currentPath);
            });
        },

        async uploadFile(file) {
            const formData = new FormData();
            formData.append('action', 'fm-upload');
            formData.append('file', file);
            formData.append('type', new URLSearchParams(window.location.search).get('file_type') || 'images');

            const response = await fetch(window.location.href, { method: 'POST', body: formData });
            if (response.ok) location.reload();
        },

        async deleteFile(path, btn) {
            if (!confirm('¿Eliminar este archivo permanentemente del servidor?')) return;
            
            logRemote("Iniciando borrado de: " + path, 'INFO');

            const formData = new FormData();
            formData.append('action', 'fm-delete-file');
            formData.append('path', path);

            try {
                const response = await fetch(window.location.href, { method: 'POST', body: formData });
                if (response.ok) {
                    btn.closest('.file-item').remove();
                    logRemote("Archivo borrado correctamente", 'INFO');
                } else {
                    logRemote("Fallo en la respuesta del servidor al borrar");
                }
            } catch (e) {
                logRemote("Error en catch deleteFile: " + e.message);
            }
        }
    };

    const contentForm = {
        prepareEdit(data) {
            const form = document.querySelector('.side-form form');
            if (!form) return;

            const container = form.closest('.side-form');
            const idInput = form.querySelector('input[id*="-id"]');
            const prefix = idInput ? idInput.id.split('-')[0] + '-' : '';
            const entityName = container.getAttribute('data-entity') || 'Elemento';

            const title = document.getElementById('form-title');
            if (title) title.innerText = `Editar ${entityName}`;

            const actionInput = form.querySelector('[name="action"]');
            if (actionInput) actionInput.value = "edit";

            Object.entries(data).forEach(([key, value]) => {
                const input = document.getElementById(prefix + key);
                if (input) {
                    input.value = (key === 'id_padre' && value === null) ? "null" : (value ?? '');
                }
            });

            ui.toggleHidden(document.getElementById('btn-cancel'), false);
            ui.scrollTo(container);
        },

        reset(btn) {
            const container = btn.closest('.side-form');
            const form = container.querySelector('form');
            if (!form) return;

            form.reset();
            const idInput = form.querySelector('input[id*="-id"]');
            if (idInput) idInput.value = "";

            const title = document.getElementById('form-title');
            const entityName = container.getAttribute('data-entity') || 'Elemento';
            if (title) title.innerText = `Nueva ${entityName}`;

            const actionInput = form.querySelector('[name="action"]');
            if (actionInput) actionInput.value = 'add';
            
            ui.toggleHidden(btn, true);
        }
    };

    const init = () => {
        fileManager.init();
        window.prepareEdit = contentForm.prepareEdit;
        window.resetForm = contentForm.reset;
        window.fileManager = fileManager;
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();