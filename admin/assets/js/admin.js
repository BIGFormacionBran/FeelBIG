function prepareEdit(data) {
    const form = document.querySelector('.side-form form');
    const formTitle = document.getElementById('form-title');
    const btnCancel = document.getElementById('btn-cancel');
    
    if (!form) return;

    // AUTOMÁTICO: Detecta prefijo (cat- o con-) desde el primer input que use el patrón de ID
    const idInput = form.querySelector('input[id*="-id"]');
    const prefix = idInput ? idInput.id.split('-')[0] + '-' : '';
    
    if (formTitle) {
        const entityName = form.closest('.side-form').getAttribute('data-entity') || 'Elemento';
        formTitle.innerText = "Editar " + entityName;
    }

    // Localización automática de inputs de control por nombre o ID
    const actionInput = form.querySelector('[name="action"]');
    if (actionInput) actionInput.value = "edit";

    // Mapeo automático de datos al formulario usando el prefijo detectado
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
    const container = btn.closest('.side-form');
    const form = container.querySelector('form');
    if (!form) return;

    form.reset();

    // Limpiar explícitamente el input de ID oculto
    const idInput = form.querySelector('input[id*="-id"]');
    if (idInput) idInput.value = "";

    const title = container.querySelector('#form-title');
    if (title) {
        const entityName = container.getAttribute('data-entity') || 'Elemento';
        title.innerText = "Nueva " + entityName;
    }
    
    const actionInput = form.querySelector('[name="action"]');
    if (actionInput) actionInput.value = 'add';
    
    btn.classList.add('hidden');
}