document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-texto');
    const textarea = document.getElementById('texto');
    const button = document.getElementById('btn-submit');

    // Validação básica: impede envio se textarea estiver vazio
    form.addEventListener('submit', function(e) {
        if (textarea.value.trim() === '') {
            e.preventDefault();
            alert('Por favor, digite um texto antes de enviar.');
            textarea.focus();
            return;
        }

        // Mostra loader no botão
        button.textContent = 'Gerando...';
        button.disabled = true;
    });

    // Animação sutil no foco do textarea
    textarea.addEventListener('focus', function() {
        textarea.style.transform = 'scale(1.02)';
    });

    textarea.addEventListener('blur', function() {
        textarea.style.transform = 'scale(1)';
    });
});