function prepareEdit(data) {
    const form = document.querySelector('.side-form form');
    const formTitle = document.getElementById('form-title');
    const btnCancel = document.getElementById('btn-cancel');
    
    if (!form) return;

    // AUTOMÁTICO: Detecta prefijo (cat- o con-) desde el primer input con guion
    const idInput = form.querySelector('input[id*="-"]');
    const prefix = idInput ? idInput.id.split('-')[0] + '-' : '';
    
    if (formTitle) {
        const entityName = form.closest('.side-form').getAttribute('data-entity') || 'Elemento';
        formTitle.innerText = "Editar " + entityName;
    }

    const actionInput = document.getElementById('form-action');
    if (actionInput) actionInput.value = "edit";

    Object.keys(data).forEach(key => {
        const input = document.getElementById(prefix + key);
        if (input) {
            if (key === 'id_padre' && data[key] === null) {
                input.value = "null";
            } else {
                input.value = data[key] !== null ? data[key] : '';
            }
        }
    });

    if (btnCancel) btnCancel.classList.remove('hidden');
    form.closest('.side-form')?.scrollIntoView({ behavior: 'smooth' });
}

function resetForm(btn) {
    // AUTOMÁTICO: Busca el formulario y el contenedor de la tarjeta
    const container = btn.closest('.side-form');
    const form = container.querySelector('form');
    if (!form) return;

    form.reset();

    // Restaurar título original desde data-entity
    const title = container.querySelector('#form-title');
    if (title) {
        const entityName = container.getAttribute('data-entity') || 'Elemento';
        title.innerText = "Nuevo " + entityName;
    }
    
    // Resetear acción a 'add'
    const actionInput = form.querySelector('#form-action');
    if (actionInput) actionInput.value = 'add';
    
    // Ocultar botón cancelar
    btn.classList.add('hidden');
}