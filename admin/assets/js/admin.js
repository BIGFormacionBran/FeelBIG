function prepareEditContent(data) {
    const formTitle = document.getElementById('form-title');
    const formAction = document.getElementById('form-action');
    const btnCancel = document.getElementById('btn-cancel');

    if(formTitle) formTitle.innerText = "Editar Contenido";
    if(formAction) formAction.value = "edit";
    
    document.getElementById('cont-id').value = data.id;
    document.getElementById('cont-nombre').value = data.nombre;
    document.getElementById('cont-id_categoria').value = data.id_categoria;
    document.getElementById('cont-clasificacion').value = data.clasificacion || '';
    document.getElementById('cont-imagen').value = data.imagen || '';
    document.getElementById('cont-video').value = data.video || '';
    document.getElementById('cont-descripcion_breve').value = data.descripcion_breve || '';
    document.getElementById('cont-enlace_externo').value = data.enlace_externo || '';
    document.getElementById('cont-fecha_publicacion').value = data.fecha_publicacion || '';
    
    if(btnCancel) btnCancel.classList.remove('hidden');
    
    document.querySelector('.side-form')?.scrollIntoView({ behavior: 'smooth' });
}

function resetForm(type) {
    const formId = type === 'category' ? 'category-form' : 'content-form';
    const form = document.getElementById(formId);
    if (form) form.reset();

    document.getElementById('form-title').innerText = (type === 'category') ? 'Nueva Categoría' : 'Nuevo Contenido';
    document.getElementById('form-action').value = 'add';
    
    const btnCancel = document.getElementById('btn-cancel');
    if(btnCancel) btnCancel.classList.add('hidden');
}