function prepareEdit(data) {
    const form = document.querySelector('.side-form form');
    const formTitle = document.getElementById('form-title');
    const btnCancel = document.getElementById('btn-cancel');
    
    if (!form) return;

    const prefix = form.id.split('-')[0].substring(0, 3) + '-';
    
    if (formTitle) {
        const entityName = document.querySelector('.side-form').getAttribute('data-entity') || 'Elemento';
        formTitle.innerText = "Editar " + entityName;
    }

    document.getElementById('form-action').value = "edit";
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
    document.querySelector('.side-form')?.scrollIntoView({ behavior: 'smooth' });
}

function resetForm(type) {
    const formId = type === 'category' ? 'category-form' : 'content-form';
    const form = document.getElementById(formId);
    if (form) form.reset();

    const title = document.getElementById('form-title');
    if (title) title.innerText = (type === 'category') ? 'Nueva Categoría' : 'Nuevo Contenido';
    
    const actionInput = document.getElementById('form-action');
    if (actionInput) actionInput.value = 'add';
    
    document.getElementById('btn-cancel')?.classList.add('hidden');
}