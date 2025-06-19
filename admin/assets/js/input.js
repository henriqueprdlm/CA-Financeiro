function ajustarAlturaTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

document.querySelectorAll('textarea.input_ajustavel').forEach(textarea => {
    textarea.addEventListener('input', () => ajustarAlturaTextarea(textarea));
});
