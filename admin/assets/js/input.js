function ajustarAlturaTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

document.querySelectorAll('textarea.input_ajustavel').forEach(textarea => {
    textarea.addEventListener('input', () => ajustarAlturaTextarea(textarea));
});

function abrirModalEditarCategoria() {
    const modal = document.getElementById('modal_editar_categoria');

    setTimeout(() => {
    const textarea = modal.querySelector('textarea.input_ajustavel');
        if (textarea) {
            ajustarAlturaTextarea(textarea);
        }
    }, 10);
}