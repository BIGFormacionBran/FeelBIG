(function() {
    "use strict";

    // --- ESTADO GLOBAL PRIVADO ---
    const state = {
        currentTargetInputId: null,
        modal: null
    };

    // --- MÓDULO: UTILIDADES ---
    const ui = {
        // Scroll suave al formulario
        scrollTo: (el) => el?.scrollIntoView({ behavior: 'smooth', block: 'start' }),
        
        // Manejo de visibilidad
        toggleHidden: (el, force) => el?.classList.toggle('hidden', force)
    };

    // --- MÓDULO: GESTOR DE ARCHIVOS ---
    const fileManager = {
        init() {
            state.modal = document.getElementById('file-manager-modal');
            if (!state.modal) return;

            // Delegación de eventos para botones de abrir y cerrar
            document.addEventListener('click', (e) => {
                // Abrir modal
                const openBtn = e.target.closest('.btn-open-filemanager');
                if (openBtn) {
                    state.currentTargetInputId = openBtn.getAttribute('data-target');
                    ui.toggleHidden(state.modal, false);
                    return;
                }

                // Cerrar modal
                if (e.target.closest('.btn-close-modal') || e.target === state.modal) {
                    ui.toggleHidden(state.modal, true);
                    return;
                }

                // Seleccionar archivo (delegación en el grid)
                const fileItem = e.target.closest('.file-item');
                if (fileItem) {
                    const path = fileItem.getAttribute('data-path'); // Asegúrate de añadir este atributo en el HTML
                    this.select(path);
                }
            });
        },

        select(path) {
            if (state.currentTargetInputId) {
                const input = document.getElementById(state.currentTargetInputId);
                if (input) input.value = path;
            }
            ui.toggleHidden(state.modal, true);
        }
    };

    // --- MÓDULO: FORMULARIOS (EDICIÓN Y RESET) ---
    const contentForm = {
        prepareEdit(data) {
            const form = document.querySelector('.side-form form');
            if (!form) return;

            const container = form.closest('.side-form');
            const idInput = form.querySelector('input[id*="-id"]');
            const prefix = idInput ? idInput.id.split('-')[0] + '-' : '';
            const entityName = container.getAttribute('data-entity') || 'Elemento';

            // Actualizar UI del formulario
            const title = document.getElementById('form-title');
            if (title) title.innerText = `Editar ${entityName}`;

            const actionInput = form.querySelector('[name="action"]');
            if (actionInput) actionInput.value = "edit";

            // Mapeo de datos optimizado
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
            
            // Limpiar ID oculto
            const idInput = form.querySelector('input[id*="-id"]');
            if (idInput) idInput.value = "";

            // Revertir UI
            const title = document.getElementById('form-title');
            const entityName = container.getAttribute('data-entity') || 'Elemento';
            if (title) title.innerText = `Nueva ${entityName}`;

            const actionInput = form.querySelector('[name="action"]');
            if (actionInput) actionInput.value = 'add';
            
            ui.toggleHidden(btn, true);
        }
    };

    // --- INICIALIZACIÓN ---
    const init = () => {
        fileManager.init();

        // Exponer funciones necesarias al scope global (para los onclick del PHP)
        // Aunque lo ideal sería delegación de eventos, mantenemos compatibilidad
        window.prepareEdit = contentForm.prepareEdit;
        window.resetForm = contentForm.reset;
        // selectFile ya no es necesaria globalmente por la delegación en fileManager.init
    };

    // Carga óptima
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

})();