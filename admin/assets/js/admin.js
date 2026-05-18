/**
 * FEEL BIG - CMS Admin Master Script
 */

/**
 * Prepara el formulario de Categorías para edición
 */
function prepareEdit(cat) {
    const formTitle = document.getElementById('form-title');
    const formAction = document.getElementById('form-action');
    const catId = document.getElementById('cat-id');
    const catNombre = document.getElementById('cat-nombre');
    const padreSelect = document.getElementById('cat-padre');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');

    if (formTitle) formTitle.innerText = 'Editar Categoría';
    if (formAction) formAction.value = 'edit';
    if (catId) catId.value = cat.id;
    if (catNombre) catNombre.value = cat.nombre;
    
    if (padreSelect) {
        padreSelect.value = (cat.id_padre === null) ? "null" : cat.id_padre;
    }
    
    if (btnSubmit) btnSubmit.innerText = 'Actualizar Cambios';
    if (btnCancel) btnCancel.style.display = 'block';
    
    document.querySelector('.side-form')?.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Prepara el formulario de Contenidos para edición
 */
function prepareEditContent(con) {
    const formTitle = document.getElementById('form-title');
    const formAction = document.getElementById('form-action');
    const contId = document.getElementById('cont-id');
    const contNombre = document.getElementById('cont-nombre');
    const contSubtitulo = document.getElementById('cont-subtitulo');
    const contDesc = document.getElementById('cont-descripcion');
    const contImg = document.getElementById('cont-imagen');
    const contCat = document.getElementById('cont-categoria');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');

    if (formTitle) formTitle.innerText = 'Editar Contenido';
    if (formAction) formAction.value = 'edit';
    if (contId) contId.value = con.id;
    if (contNombre) contNombre.value = con.nombre;
    if (contSubtitulo) contSubtitulo.value = con.subtitulo;
    if (contDesc) contDesc.value = con.descripcion;
    if (contImg) contImg.value = con.imagen;
    if (contCat) contCat.value = con.id_categoria;
    
    if (btnSubmit) btnSubmit.innerText = 'Actualizar Cambios';
    if (btnCancel) btnCancel.style.display = 'block';
    
    document.querySelector('.side-form')?.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restablece cualquier formulario administrativo
 */
function resetForm(type) {
    const formId = type === 'category' ? 'category-form' : 'content-form';
    const form = document.getElementById(formId);
    const formTitle = document.getElementById('form-title');
    const formAction = document.getElementById('form-action');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');

    if (form) form.reset();
    if (formTitle) formTitle.innerText = type === 'category' ? 'Nueva Categoría' : 'Nuevo Contenido';
    if (formAction) formAction.value = 'add';
    
    // Limpiar IDs ocultos
    const hiddenId = document.getElementById(type === 'category' ? 'cat-id' : 'cont-id');
    if (hiddenId) hiddenId.value = '';

    if (btnSubmit) btnSubmit.innerText = type === 'category' ? 'Guardar Categoría' : 'Guardar Contenido';
    if (btnCancel) btnCancel.style.display = 'none';
}